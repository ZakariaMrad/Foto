<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <form class="form-group" @submit.prevent="submitFn">
                    <h3 class="text-center" md="12">Créer un post</h3>
                    <v-row>
                        <v-col cols="6">
                            <div class="left-panel">
                                <v-text-field v-bind="register('title')" type="text" label="Titre" required />
                                <v-switch hide-details color="blue"
                                    v-bind:input-value="register('isPublic', { defaultValue: true })" label="Public" />
                            </div>
                        </v-col>
                        <v-col cols="6">
                            <div class="right-panel">
                                <v-textarea rows="4" v-bind="register('description')" type="" label="Description"
                                    required />
                            </div>
                        </v-col>
                    </v-row>
                    <p class="text-danger" v-for="error in errors">{{ error.message }}</p>

                    <p class="text-success">{{ message }}</p>

                    <v-tabs v-model="tab" color="deep-purple-accent-4" fixed-tabs>
                        <v-tab value="0" prepend-icon="mdi-panorama-variant-outline">Fotos</v-tab>
                        <v-tab value="1" prepend-icon="mdi-panorama-variant-outline">Albums</v-tab>
                    </v-tabs>
                    <v-card-text>
                        <v-window v-model="tab">
                            <v-window-item value="0">
                                <AssetPicker :itemSize="6" :items="fotos" :multiple="false"
                            title="" @items-selected="(items) => setItems(items as Foto[])" />
                            </v-window-item>

                            <v-window-item value="1">
                                <AssetPicker :itemSize="6" :items="albums" :multiple="false"
                                title="" @items-selected="(items) => setItems(items as Album[])" />
                            </v-window-item>
                        </v-window>
                    </v-card-text>
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <div class="float-right">
                        <v-btn type="submit" class="text-white" color="green-darken-3" text="Créer" :loading="loading" />
                    </div>
                </form>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useFormHandler } from 'vue-form-handler';
import Foto from '../../models/Foto';
import AssetPicker from '../AssetPicker.vue';
import FotoRepository from '../../repositories/FotoRepository';
import PostRepository from '../../repositories/PostRepository';
import { APIError } from '../../core/API/APIError';
import { watch } from 'vue';
import { EventsBus, Events } from '../../core/EventBus';
import AlbumRepository from '../../repositories/AlbumRepository';
import Album from '../../models/Album';

const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})

const { eventBusEmit } = EventsBus();


const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

const errors = ref<APIError[]>([])
const message = ref<string | undefined>('')
const loading = ref<boolean>(false)
const tab = ref<number>(0)

const fotos = ref<Foto[]>([])
const albums = ref<Album[]>([])
let choosenFotoId: number | undefined = undefined

watch(() => props.activate, async () => {
    console.log('activate', props.activate);
    
    let apiResponse = await FotoRepository.getFotos()
    if (!apiResponse.success) return
    fotos.value = apiResponse.data

    let apiResponseAlbums = await AlbumRepository.getAlbums()
    console.log('albums',apiResponseAlbums);
    if(!apiResponseAlbums.success) return
    albums.value = apiResponseAlbums.data
    

})

function setItems(items: (Foto|Album)[]) {
    if(items.length == 0) return
    if('idFoto' in items[0]){
        choosenFotoId = items[0].idFoto
    } else {
        choosenFotoId = items[0].idAlbum
    }
    console.log(choosenFotoId);
}

async function successFn(form: any) {
    loading.value = true
    form.id = choosenFotoId;
    form.isFoto = tab.value == 0?'true':'false';

    let apiResult = await PostRepository.createPost(form)
    loading.value = false
    errors.value = []
    message.value = ''
    if (!apiResult.success) {
        errors.value = apiResult.errors
        return
    }
    loading.value = false

    message.value = apiResult.data.message
    eventBusEmit(Events.RELOAD_CONNECTED_ACCOUNT, undefined)

    closeDialog();
}

function submitFn() {
    try {
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}
function closeDialog() {
    emit('closeDialog');
}
</script>

<style scoped></style>