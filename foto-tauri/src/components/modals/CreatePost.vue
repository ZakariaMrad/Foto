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
                                <v-switch hide-details color="blue" v-bind:input-value="register('isPublic', { defaultValue: true})"
                                    label="Public" />
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
                    <v-btn-toggle class="d-flex justify-center" color="blue" v-model="isFotos" mandatory>
                        <v-btn value="0">Fotos</v-btn>
                        <v-btn value="1">Albums</v-btn>
                    </v-btn-toggle>
                    <div class="h-10">
                        <AssetPicker :itemSize="6" :items="fotos" :multiple="false"
                            @items-selected="(items) => setItems(items)" />
                    </div>
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

const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})
const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

const errors = ref<APIError[]>([])
const message = ref<string | undefined>('')
const loading = ref<boolean>(false)

const isFotos = ref<number>(0)
const fotos = ref<Foto[]>([])
let choosenFotoId: number | undefined = undefined
onMounted(async () => {
    let apiResponse = await FotoRepository.getFotos()
    console.log(apiResponse);
    if (!apiResponse.success) return

    fotos.value = apiResponse.data
})
function setItems(items: Foto[]) {
    console.log(items);
    choosenFotoId = items[0].idFoto
    console.log(choosenFotoId);
}

async function successFn(form: any) {
    loading.value = true
    form.id = choosenFotoId;
    form.isFoto = 'true';
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