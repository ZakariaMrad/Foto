<template>
    <div>
        <form @submit.prevent="submitFn">
            <v-row>
                <v-col>
                    <v-row>
                        <v-col cols="10">
                            <v-text-field label="Raison du blockage" required v-bind="register('reason')"
                                :loading="loading" />
                            <p class="text-danger">{{ errorMessage }} </p>
                            <p class="text-success">{{ successMessage }} </p>
                        </v-col>
                        <v-col cols=2>
                            <v-btn type="submit" :loading="loading" class="text-white" color="green-darken-3"
                                text="Suivant" />
                        </v-col>

                    </v-row>
                    <PickUser @user="chooseUser"></PickUser>
                </v-col>
            </v-row>
        </form>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import AccountRepository from '../../repositories/AccountRepository';
import UserBlockRepository from '../../repositories/UserBlockRepository';
import Account from '../../models/Account';
import PickUser from './pickUser.vue';
import { useFormHandler } from 'vue-form-handler';


const emit = defineEmits<{ (event: 'done'): void }>()

const { register, handleSubmit, formState, unregister } = useFormHandler({
    validationMode: 'always',
})
const accounts = ref<Account[]>([])
const loading = ref<boolean>(false)
const errorMessage = ref<string | undefined>("");
const successMessage = ref<string | undefined>("");

onMounted(async () => {
    let apiResult = await AccountRepository.searchUser(' ')
    if (!apiResult.success) return;
    if (!apiResult.data) return;
    accounts.value = apiResult.data;
})

function chooseUser(account: Account) {
    unregister('user');
    register('user', { defaultValue: account, required: true });
}
const successFn = async (form: any) => {
    errorMessage.value = undefined;
    successMessage.value = undefined;
    if (!form.user) {
        errorMessage.value = "Veuillez choisir un utilisateur";
        return;
    }
    console.log(form, form.reason);
    

    let apiResult = await UserBlockRepository.blockUser(form.reason, form.user.idAccount);
    if (!apiResult.success) {
        errorMessage.value = apiResult.errors![0].message;
        return
    }
    if (!apiResult.data) return
    successMessage.value = apiResult.data.message;
    emit('done');
    loading.value = false;

}

const submitFn = () => {
    try {
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}
</script>

<style scoped></style>