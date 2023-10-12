<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card title="Modification de profil">
            <form class="form-group" @submit.prevent="submitFn">
                <hr>
                <v-row cols="12" class="pa-2">
                    <v-col cols="6">
                        <p class="ml-10">Choisir une photo de profile</p>
                        <AssetPicker :itemSize="2" :items="fotos" :multiple="false" @items-selected="(items) => setItems(items)" label="Choisir une photo"></AssetPicker>
                    </v-col>
                    <v-col cols="6">
                        <p>Ajouter une bio</p>
                        <v-textarea v-bind="register('bio')" type="text" label="Entrer du texte"></v-textarea>
                    </v-col>
                </v-row>
                <v-card-actions>
                    <v-spacer />
                    <v-btn text="Fermer" @click="closeDialog" />
                    <div class="float-right">
                        <v-btn type="submit" class="text-white" color="green-darken-3" text="Publier" :loading="loading" />
                    </div>
                </v-card-actions>
            </form>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { APIError } from '../../core/API/APIError';
import { useFormHandler } from 'vue-form-handler';
import ModifyProfileRepository from '../../repositories/ModifyProfileRepository';
import Foto from '../../models/Foto';
import AssetPicker from '../AssetPicker.vue';





const loading = ref<boolean>(false)
const errors = ref<APIError[]>([])
const message = ref<string | undefined>('')
const emit = defineEmits(['closeDialog'])
const fotos = ref<Foto[]>([])
let choosenFotoId: number | undefined = undefined
const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})



const props = defineProps({
    activate: Boolean
})


function setItems(items: Foto[]) {
    console.log(items);
    choosenFotoId = items[0].idFoto
    console.log(choosenFotoId);
}

async function successFn(form: any) {
    loading.value = true
    // form.id = choosenFotoId;
    form.isFoto = 'true';
    console.log('form', form);

    let apiResult = await ModifyProfileRepository.modifyAccount(form)
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