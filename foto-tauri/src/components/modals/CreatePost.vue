<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <form class="form-group" @submit.prevent="submitFn">
                    <h3 class="text-center" md="12">Créer une publication</h3>
                    <v-row>
                        <v-col cols="6">
                            <div class="left-panel">
                                <v-text-field v-bind="register('title')" type="text" label="Titre" required />
                                <v-switch hide-details color="blue"
                                    v-bind:input-value="register('isPublic', { defaultValue: true })" label="Publique" />
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
                    <v-responsive :aspect-ratio="3 / 1">
                        <v-card height="99%">
                            <v-tabs v-model="isFotos" color="deep-purple-accent-4" fixed-tabs>
                                <v-tab value="0">Fotos</v-tab>
                                <v-tab value="1">Albums</v-tab>
                            </v-tabs>
                            <v-card-text>
                                <v-window v-model="isFotos">
                                    <v-window-item value="0">
                                        <AssetPicker :itemSize="6" :items="fotos.reverse()" :multiple="false" title=""
                                            @items-selected="(items) => setItems(items as Foto[])" />
                                    </v-window-item>
                                    <v-window-item value="1">
                                        <AssetPicker :itemSize="6" :items="albums.reverse()" :multiple="false" title=""
                                            @items-selected="(items) => setItems(items as Album[])" />
                                    </v-window-item>
                                </v-window>
                            </v-card-text>
                        </v-card>
                    </v-responsive>
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
import { onMounted, ref } from 'vue';
import { useFormHandler } from 'vue-form-handler';
import Foto from '../../models/Foto';
import AssetPicker from '../AssetPicker.vue';
import FotoRepository from '../../repositories/FotoRepository';
import PostRepository from '../../repositories/PostRepository';
import { APIError } from '../../core/API/APIError';
import { EventsBus, Events } from '../../core/EventBus';
//@ts-ignore
import delay from 'delay';
import Album from '../../models/Album';
import AlbumRepository from '../../repositories/AlbumRepository';

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

const isFotos = ref<number>(0)
const fotos = ref<Foto[]>([])
const albums = ref<Album[]>([])
let choosenId: number | undefined = undefined
onMounted(async () => {
    //We need to do the the get at the same time
    await Promise.all([getFotos(), getAlbums()])
    console.log(fotos.value, albums.value);

})
async function getFotos() {
    let apiResponse = await FotoRepository.getFotos()
    console.log(apiResponse);
    if (!apiResponse.success) return
    fotos.value = apiResponse.data
}
async function getAlbums() {
    let apiResponse = await AlbumRepository.getAlbums()
    console.log(apiResponse);
    if (!apiResponse.success) return
    albums.value = apiResponse.data
}
function setItems(items: Foto[] | Album[]) {
    console.log(items);
    if (items.length == 0) return
    choosenId = undefined
    //check is it foto or album 
    if ('idFoto' in items[0]) {
        choosenId = items[0].idFoto
    } else {
        choosenId = items[0].idAlbum
    }
}

async function successFn(form: any) {
    loading.value = true
    form.id = choosenId;
    form.isFoto = !isFotos?'true':'false';
    console.log('form', form);

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
async function closeDialog() {
    await delay(2000);
    emit('closeDialog');
}
</script>

<style scoped></style>