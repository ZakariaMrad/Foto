<template>
    <header>
        <nav class="py-2 bg-light border-bottom">
            <div class="container d-flex flex-wrap">
                <ul class="nav me-auto">
                    <li class="nav-item">
                        <router-link class="nav-link link-dark" :to="{ name: 'home' }">Accueil</router-link>
                    </li>
                    <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Features</a></li>
                    <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Pricing</a></li>
                    <li class="nav-item"><a href="#" class="nav-link link-dark px-2">FAQs</a></li>
                    <li class="nav-item"><a href="#" class="nav-link link-dark px-2">About</a></li>
                </ul>
                <ul class="nav">
                    <li class="nav-item">
                        <button v-if="!userConnected" class="nav-link link-dark btn btn-primary" @click="activateLogin = true">Connexion</button>
                        <button v-else class="nav-link link-dark btn btn-danger" @click="logout()">Deconnexion</button>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <LoginRegister :activate="activateLogin" @isActivated="(value) => activateLogin = value" @loggedIn="userConnected=true" />
</template>

<script setup lang="ts">
import AccountRepository from '../repositories/AccountRepository'

import { ref } from 'vue'
import LoginRegister from './LoginRegister.vue';
import { onMounted } from 'vue';

const activateLogin = ref(false);
const userConnected = ref<boolean>(false);

const logout = async () => {
    await AccountRepository.logout();
    userConnected.value = false;
}

onMounted(async () => {
    userConnected.value= await AccountRepository.isConnected();
    
})

</script>

<style scoped></style>