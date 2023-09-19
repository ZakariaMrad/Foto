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
                        <button class="nav-link link-dark btn" @click="activateLogin=true">Login</button>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <LoginRegister :activate="activateLogin" @isActivated="(value)=>activateLogin= value"/>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import LoginRegister from './LoginRegister.vue';

const activateLogin = ref(false);
</script>

<style scoped></style>