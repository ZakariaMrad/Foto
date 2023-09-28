<template>
    <h3 class="text-center">Create an account</h3>
    <form class="form-group" @submit.prevent="submitFn">
        <v-text-field v-bind="register('name')" type="text" label="Name" required></v-text-field>
        <v-text-field v-bind="register('location')" type="text" label="Adress" required></v-text-field>
        <v-text-field v-bind="register('email')" type="email" label="Email" required></v-text-field>
        <v-text-field v-bind="register('passwordFirst')" type="password" label="Password" required></v-text-field>
        <v-text-field v-bind="register('passwordSecond')" type="password" label="Password Confirmation"
        required></v-text-field>
        <v-text-field v-bind="register('birthDate')" type="date" label="Birth Date" required></v-text-field>

        <p class="text-danger" v-for="error in errors">{{ error.message }}</p>

        <p class="text-success">{{ message }}</p>
        <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Close Dialog</v-btn>
        <div class="float-right">
            <v-btn class="text-white me-1" color="blue-accent-3" variant="outlined" @click="toggleRegister()">Login</v-btn>
            <v-btn type="submit" class="text-white" color="green-darken-3" text="Submit" :loading="loading"/>
        </div>
    </form>
</template>

<script setup lang="ts">
import { useFormHandler } from 'vue-form-handler'
import AccountRepository from '../repositories/AccountRepository'
import { ref } from 'vue'
import { APIError } from '../core/API/APIError';
import RegistrationAccount from '../models/RegistrationAccount';

const emit = defineEmits(['closeDialog', 'isRegister'])

const errors = ref<APIError[]>([])
const message = ref<string | undefined>('')
const loading = ref<boolean>(false)

function closeDialog(val: boolean = false) {
    emit('closeDialog', val);
}

function toggleRegister() {
    emit('isRegister', true);
}

const { register, handleSubmit, formState } = useFormHandler({ validationMode: 'always' })

const successFn = async (form: any) => {
    loading.value = true
    let registrationAccount = new RegistrationAccount(form.name, form.location, form.birthDate, form.email, form.passwordFirst, form.passwordSecond)

    let apiResult = await AccountRepository.register(registrationAccount)
    loading.value = false
    errors.value = []
    message.value = ''

    if (!apiResult.success) {
        errors.value = apiResult.errors
        return
    }
    message.value = apiResult.data.message
    closeDialog(true);
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