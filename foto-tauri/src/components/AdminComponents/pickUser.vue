<template>
    <form @submit.prevent="submitFn">
        <v-text-field v-bind="register('search')" type="text" label="Recherche" :loading="loading" @input="submitFn" />
        <v-table fixed-header height="30vh">
            <thead>
                <tr>
                    <th style="width:20%;">Nom</th>
                    <th style="width:70%;">Email</th>
                    <th style="width: 10%;text-align: center;">Choisir</th>
                </tr>
            </thead>
            <tbody>
                <UserSelect v-for="account in accounts" :account="account" :isChoosen=" account.idAccount == choosenAccount?.idAccount" @select-user="selectUser"/>
            </tbody>
        </v-table>
    </form>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useFormHandler } from 'vue-form-handler';
import AccountRepository from '../../repositories/AccountRepository';
import Account from '../../models/Account';
import UserSelect from './userSelect.vue';


const loading = ref<boolean>(false)
const accounts = ref<Account[]>([])
const choosenAccount = ref<Account | undefined>()

const emit = defineEmits<{ 
    (event: 'user', user: Account): void 
}>()

onMounted(() => {    
    submitFn();    
})

const { register, handleSubmit, formState } = useFormHandler({ validationMode: 'always' })

function submitFn() {
    try {
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
    loading.value = false
}
function selectUser(user: Account) {
    if (choosenAccount.value?.idAccount == user.idAccount) {
        choosenAccount.value = undefined
        return
    }
    choosenAccount.value = user
    emit('user', user)
}

</script>

<style scoped></style>