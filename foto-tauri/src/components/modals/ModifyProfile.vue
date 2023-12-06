<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card title="Modification de profil">
            <form class="form-group" @submit.prevent="submitFn">
                <hr>
                <v-row cols="12" class="pa-2">
                    <v-col cols="6">
                        <p class="ml-10">Choisir une photo de profile</p>
                        <v-file-input label="Parcourir..." variant="solo" accept="image/png, image/jpeg, image/webp"
                             chips prepend-icon="mdi-camera" v-model="files">
                        </v-file-input>
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
import { ref, watch } from 'vue';
import { APIError } from '../../core/API/APIError';
import { useFormHandler } from 'vue-form-handler';
import AccountRepository from '../../repositories/AccountRepository';
import Account from '../../models/Account';
import { Events, EventsBus } from '../../core/EventBus';





const loading = ref<boolean>(false)
const errors = ref<APIError[]>([])
const message = ref<string | undefined>('')
const emit = defineEmits(['closeDialog'])
const { eventBusEmit, bus } = EventsBus();
const files = ref<File[]>([]);


let account: Account | undefined;


const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (value: Account[] | undefined) => {
    // console.log(value);

    if (!value) {
        account = undefined;
        return;
    }
    account = value[0];
})

const props = defineProps({
    activate: Boolean
})

function fileToBase64(file : File) : Promise<string | ArrayBuffer | null> {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onloadend = () => resolve(reader.result);
        reader.onerror = reject;
        reader.readAsDataURL(file);
    });
}

async function successFn(form: Partial<Account>) {
    loading.value = true
    // form.id = choosenFotoId;
    console.log('form', form, account);

    let completedAccount = { ...account, ...form }

    console.log(completedAccount);

    const base64 = await fileToBase64(files.value[0]);
    if(!base64) return;
    let apiResult = await AccountRepository.updateAccount(completedAccount as Account, base64)
    console.log(apiResult);

    loading.value = false
    errors.value = []
    message.value = ''
    if (!apiResult.success) {
        errors.value = apiResult.errors
        return
    }
    loading.value = false
    console.log(apiResult);

    message.value = apiResult.data.message
    // message.value = "Compte modifié avec succès"

    eventBusEmit(Events.CONNECTED_ACCOUNT, apiResult.data)

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