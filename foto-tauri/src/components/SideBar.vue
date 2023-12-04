<template>
    <v-main>
        <v-navigation-drawer expand-on-hover rail permanent>
            <v-list v-if="account" density="compact">
                <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" :title="account?.name"
                    :subtitle="account?.email" />
            </v-list>
            <v-list v-else density="compact">
                <v-list-item prepend-icon="mdi-login-variant" title="Connexion" @click="Login" />
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-glasses" title="Rechercher" @click="openSearchModal" />
                <v-list-item prepend-icon="mdi-home" title="Accueil" :to="{ name: 'home' }"></v-list-item>
                <v-list-item v-if="account" prepend-icon="mdi-account" title="Mon profil" :to="{ name: 'profil' }"></v-list-item>
                <v-list-item v-if="account" prepend-icon="mdi-account-heart" title="Mes amis" :to="{name: 'friendsList'}"/>
                <v-list-item v-if="account" prepend-icon="mdi-upload" title="Téléverser" :to="{ name: 'upload' }"></v-list-item>
                <v-list-item v-if="account" prepend-icon="mdi-logout-variant" title="Se déconnecter" @click="logout" />
            </v-list>
        </v-navigation-drawer>
        <div class="mx-16 my-1">
            <slot></slot>
        </div>
    </v-main>
    
</template>
<script setup lang="ts">
import {  onMounted, ref, watch } from 'vue';
import  Account  from '../models/Account';
import {EventsBus, Events} from '../core/EventBus';
const {eventBusEmit,bus} = EventsBus();


const account = ref<Account | undefined>(undefined);

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (value: Account[] | undefined) => {
    // console.log('connected account', value);
    
    if (!value) {
        account.value = undefined;
        console.log(account.value);
        
        return;
    }
    account.value = value[0];
    
})

//La ligne fix le bug du fait que le compte ne se charge pas lors d'un changement de page
onMounted(() => {
    console.log(account.value);
    
    eventBusEmit(Events.RELOAD_CONNECTED_ACCOUNT, undefined)
})

function Login() {
    eventBusEmit(Events.LOGIN)
}

async function logout() {
    eventBusEmit(Events.LOGOUT)
}

function openSearchModal() {
    eventBusEmit(Events.OPEN_SEARCH_MODAL)
}
</script>
