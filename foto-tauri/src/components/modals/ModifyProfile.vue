<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card title="Modification de profil">
            <form class="form-group" @submit.prevent="submitFn">
                <hr>
                <v-row cols="12" class="pa-2">
                    <v-col cols="6">
                        <p class="ml-10">Choisir une photo de profile</p>
                        <AssetPicker :itemSize="2" :items="fotos" :multiple="false"
                            @items-selected="(items) => setItems(items)" label="Choisir une photo"></AssetPicker>
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
import Foto from '../../models/Foto';
import AssetPicker from '../AssetPicker.vue';
import AccountRepository from '../../repositories/AccountRepository';
import Account from '../../models/Account';
import { Events, EventsBus } from '../../core/EventBus';
import Album from '../../models/Album';





const loading = ref<boolean>(false)
const errors = ref<APIError[]>([])
const message = ref<string | undefined>('')
const emit = defineEmits(['closeDialog'])
const fotos = ref<Foto[]>([])
const { eventBusEmit, bus } = EventsBus();


let choosenFotoId: number | undefined = undefined
let account: Account | undefined;


const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (value: Account[] | undefined) => {
    console.log(value);

    if (!value) {
        account = undefined;
        return;
    }
    account = value[0];
})

const props = defineProps({
    activate: Boolean
})


function setItems(items: (Foto | Album)[]) {
    console.log(items);
    if (!('idFoto' in items[0])) return;
    choosenFotoId = items[0].idFoto
    console.log(choosenFotoId);
}

async function successFn(form: Partial<Account>) {
    loading.value = true
    // form.id = choosenFotoId;
    console.log('form', form, account);

    let completedAccount = { ...account, ...form }

    console.log(completedAccount);



    let apiResult = await AccountRepository.updateAccount(completedAccount as Account)
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