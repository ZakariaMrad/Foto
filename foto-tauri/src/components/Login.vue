<template>
    <form class="form-group" @submit.prevent="submitFn">
        <div class="form-outline mb-4">
            <input type="email" id="email" class="form-control" v-bind="register('email')" />
            <label class="form-label" for="email">Email address</label>
        </div>

        <div class="form-outline mb-4">
            <input type="password" id="password" class="form-control" v-bind="register('password')" />
            <label class="form-label" for="password">Password</label>
        </div>
        <p class="text-danger">error</p>
        <button class="btn btn-danger" @click="closeDialog()">Close Dialog</button>
        <div class="btn-group float-right">
            <button class="btn btn-outline-primary">Register</button>
            <input type="submit" class="btn btn-success text-white" />
        </div>
    </form>
</template>

<script setup lang="ts">
import { useFormHandler } from 'vue-form-handler'
import AccountRepository from '../repositories/AccountRepository'
import { LoginAccount } from '../models/LoginAccount';

const emit = defineEmits(['isActivated'])
function closeDialog() {
    emit('isActivated', false);
}

const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})

const successFn = async (form: any) => {
    //do anything with form
    let a = await AccountRepository.login(form as LoginAccount)
    console.log(a)
    console.log(form as LoginAccount)
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