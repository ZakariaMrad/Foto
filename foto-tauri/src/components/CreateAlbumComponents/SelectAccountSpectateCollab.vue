<template>
    <form @submit.prevent="submitFn">
        <v-text-field v-bind="register('search')" :disabled="props.disabled" type="text" label="Recherche" :loading="loading" @input="submitFn" />
        <v-table fixed-header height="50vh">
            <thead>
                <tr>
                    <th style="width:20%;">Nom</th>
                    <th style="width:70%;">Email</th>
                    <th style="width: 5%;text-align: center;">Spectateur</th>
                    <th style="width: 5%;text-align: center;">Collaborateur</th>
                </tr>
            </thead>
            <tbody>
                <SpectatorCollaborater :disabled="props.disabled" v-for="account in accounts" :isPublic="props.isPublic!" :account="account" :key="account.idAccount"
                :isSpectator="isSpectator(account)" :isCollaborater="isCollaborater(account)"
                    @addCollaborater="(idAccount) => addCollaborater(idAccount)"
                    @removeCollaborater="(idAccount) => removeCollaborater(idAccount)"
                    @addSpectator="(idAccount) => addSpectator(idAccount)"
                    @removeSpectator="(idAccount) => removeSpectator(idAccount)" />
            </tbody>
        </v-table>
    </form>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useFormHandler } from 'vue-form-handler';
import AccountRepository from '../../repositories/AccountRepository';
import Account from '../../models/Account';
import SpectatorCollaborater from './SpectatorCollaborater.vue';

const props = defineProps<{ isPublic: boolean, disabled:boolean }>()

const loading = ref<boolean>(false)
const accounts = ref<Account[]>([])
const collaborators = ref<Account[]>([])
const spectators = ref<Account[]>([])

const emit = defineEmits<{ 
    (event: 'collaboraters', user: Account[]): void 
    (event: 'spectators', user: Account[]): void 
}>()

onMounted(() => {
    submitFn();
    console.log('collab', props.isPublic);
    
})

const { register, handleSubmit, formState } = useFormHandler({ validationMode: 'always' })

function submitFn() {
    try {
        emit('collaboraters', collaborators.value)
        emit('spectators', spectators.value)
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}
async function successFn(form: any) {
    loading.value = true
    console.log(form.search || '');

    let apiResult = await AccountRepository.searchUser(form.search || ' ');
    if (!apiResult.success) {
        console.log(apiResult.errors);
        return
    }
    if (!apiResult.data) return
    accounts.value = apiResult.data
    //we need to get the connected account

    const apiResultAccount = await AccountRepository.getAccount()
    if (!apiResultAccount.success) {
        console.log(apiResultAccount.errors);
        return
    }
    if (!apiResultAccount.data) return
    accounts.value = accounts.value.filter((ac) => ac.idAccount !== apiResultAccount.data!.idAccount)


    loading.value = false
}

function isSpectator(account: Account) {
    return spectators.value.find((ac) => ac.idAccount === account.idAccount) !== undefined
}
function isCollaborater(account: Account) {
    return collaborators.value.find((ac) => ac.idAccount === account.idAccount) !== undefined
}

function addCollaborater(account: Account) {
    collaborators.value.push(accounts.value.find((ac) => ac.idAccount === account.idAccount)!)
    removeSpectator(account)
}
function removeCollaborater(account: Account) {
    collaborators.value = collaborators.value.filter((ac) => ac.idAccount !== account.idAccount)
    removeSpectator(account)
}

function addSpectator(account: Account) {    
    spectators.value.push(accounts.value.find((ac) => ac.idAccount === account.idAccount)!)
}
function removeSpectator(account: Account) {
    spectators.value = spectators.value.filter((ac) => ac.idAccount !== account.idAccount)
}

</script>

<style scoped></style>