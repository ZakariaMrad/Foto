<template>
    <form @submit.prevent="submitFn">
        <v-text-field v-bind="register('search')" type="text" label="Recherche" :loading="loading" @input="submitFn" />
        <v-table fixed-header height="50vh">
            <thead>
                <tr>
                    <th style="width:20%;">Utilisateur</th>
                    <th style="width:70%;">Raison</th>
                    <th style="width: 10%;text-align: center;">Débloquer</th>
                </tr>
            </thead>
            <tbody>
                <BlockedUserSelect v-for="blockedAccount in blockedAccounts" :blockedUser="blockedAccount" @select-user="unblock"/>
            </tbody>
        </v-table>
    </form>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useFormHandler } from 'vue-form-handler';
import BlockedUserSelect from './blockedUserSelect.vue';
import UserBlockRepository from '../../repositories/UserBlockRepository';
import UserBlock from '../../models/UserBlock';


const loading = ref<boolean>(false)
const blockedAccounts = ref<UserBlock[]>([])

const emit = defineEmits<{ 
    (event: 'user', user: UserBlock): void 
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

    let apiResult = await UserBlockRepository.searchUser(form.search || ' ');
    if (!apiResult.success) {
        console.log(apiResult.errors);
        return
    }
    if (!apiResult.data) return
    blockedAccounts.value = apiResult.data
    loading.value = false
}
async function unblock(user: UserBlock) {
   console.log('unblock',user);
   let apiResult = await UserBlockRepository.deleteUserBlock(user.idUserBlock);
   if (!apiResult.success) {
       console.log(apiResult.errors);
       return
   }
    submitFn();
}

</script>

<style scoped></style>