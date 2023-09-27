<template>
    <v-app-bar>
        <v-toolbar app>
            <v-toolbar-title class="text-uppercase grey--text d-flex flex-wrap" height="auto">
                <span class="font-weight-bold ma-2 pa-2">Foto</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn color="grey" @click="activateLogin = true" v-if="!userConnected">Se connecter</v-btn>
            <v-btn color="grey" @click="logout()" v-else>Se déconnecter</v-btn>

            <div class="text-center ma-2 pa-2">
                <v-menu open-on-hover>
                    <template v-slot:activator="{ props }">
                        <v-btn color="primary" v-bind="props" icon="mdi-menu">
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item v-for="link in links" :key="link.text" router :to="link.route">
                            <v-list-item-title>
                                <v-icon>{{ link.icon }}</v-icon>
                                {{ link.text }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>
        </v-toolbar>
    </v-app-bar>
    <LoginRegister :activate="activateLogin" @isActivated="(value) => activateLogin = value" @loggedIn="userConnected=true" />
</template>

<script setup lang="ts">

import AccountRepository from '../repositories/AccountRepository'

import { ref, onMounted } from 'vue'
import LoginRegister from './LoginRegister.vue';

const activateLogin = ref(false);
const userConnected = ref<boolean>(false);

const logout = async () => {
    await AccountRepository.logout();
    userConnected.value = false;
}

const links = ref([
                { icon: 'mdi-theme-light-dark', text: 'Dark mode', route: '' },
                { icon: 'mdi-account-plus', text: `S'inscrire`, route: '' },
                { icon: 'mdi-cog-outline', text: 'Paramètres', route: '' },
                { icon: 'mdi-card-account-phone-outline', text: 'Nous contacter', route: '' },
            ]);

onMounted(async () => {
    userConnected.value= await AccountRepository.isConnected();
    
})

            
</script>


