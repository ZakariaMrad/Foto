<template>
    <form class="form-group" @submit.prevent="submitFn">
        <v-text-field v-bind="register('email')" type="email" label="Email" required></v-text-field>

        <v-text-field v-bind="register('password')" type="password" label="Password" required></v-text-field>
        <p class="text-danger" v-for="error in errors">{{ error.propertyName }} : {{ error.message }}</p>

        <p class="text-success">{{ message }}</p>
        <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Close Dialog</v-btn>
        <div class="float-right">
            <v-btn class="text-white me-1" color="blue-accent-3" variant="outlined"
                @click="toggleRegister()">Register</v-btn>
            <v-btn type="submit" class="text-white" color="green-darken-3" text="Submit" />
        </div>
    </form>
</template>

<script setup lang="ts">
import { useFormHandler } from 'vue-form-handler'
import AccountRepository from '../repositories/AccountRepository'
import { LoginAccount } from '../models/LoginAccount';
import { ref } from 'vue'
import { APIError } from '../core/API/APIError';

const emit = defineEmits(['isActivated', 'loggedIn', 'isRegister'])

const errors = ref<APIError[]>([])
const message = ref<string>('')

function closeDialog() {
    emit('isActivated', false);
}
function sendLoggedIn() {
    emit('loggedIn', true);
}
function toggleRegister() {
    emit('isRegister', true);
}

const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})

const successFn = async (form: any) => {
    console.log(form);

    let apiResult = await AccountRepository.login(form as LoginAccount)
    errors.value = []
    message.value = ''

    if (!apiResult.success) {
        errors.value = apiResult.errors
        return
    }
    message.value = apiResult.data.message
    console.log(apiResult);
    return;

    sendLoggedIn();
    closeDialog();
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

<style scoped>
p::first-letter {
    text-transform: capitalize;
}
</style>