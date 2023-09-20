<template>
    <form class="form-group" @submit.prevent="submitFn">
        <div class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-bind="register('email')"/>
            <label class="form-label" for="email">Email address</label>
        </div>

        <div class="form-outline mb-4">
            <input type="password" id="password" class="form-control" v-bind="register('password')" />
            <label class="form-label" for="password">Password</label>
        </div>
        <p class="text-danger" v-for="error in errors">{{ error.propertyName }} : {{ error.message }}</p>

        <p class="text-success">{{ message }}</p>
        <button class="btn btn-danger" @click="closeDialog()">Close Dialog</button>
        <div class="btn-group float-right">
            <button class="btn btn-outline-primary" @click="isRegister()">Register</button>
            <input type="submit" class="btn btn-success text-white" />
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
function isRegister() {
    emit('isRegister', true);
}

const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})

const successFn = async (form: any) => {
    let apiResult = await AccountRepository.login(form as LoginAccount)
    errors.value = []
    message.value = ''

    if (!apiResult.success) {
        errors.value = apiResult.errors
        return
    }
    message.value = apiResult.data.message
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