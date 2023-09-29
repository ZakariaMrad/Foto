<template>
    <v-main>
        <v-navigation-drawer expand-on-hover rail>
            <v-list v-if="account" density="compact">
                <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" :title="account?.name"
                    :subtitle="account?.email" />
            </v-list>
            <v-list v-else density="compact">
                <v-list-item prepend-icon="mdi-login-variant" title="Connexion" @click="Login" />
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-folder" title="Acceuil" value="myfiles" />
                <v-list-item prepend-icon="mdi-account-multiple" title="Populaire" value="shared" />
                <v-list-item prepend-icon="mdi-glasses" title="Ouvrir le modal" @click="openSearchModal" />
                <v-list-item prepend-icon="mdi-plus-box" title="Create a post" @click="createPost" />
                <v-list-item v-if="account" prepend-icon="mdi-logout-variant" title="Logout" @click="logout" />
            </v-list>
        </v-navigation-drawer>
        <div class="mx-16 my-1">
            <slot></slot>
        </div>
    </v-main>
    
</template>
<script setup lang="ts">
import {  ref, watch } from 'vue';
import { Account } from '../models/Account';
import {EventsBus, Events} from '../core/EventBus';
const {eventBusEmit,bus} = EventsBus();


const emit = defineEmits(['loggedIn', 'logout'])
const account = ref<Account | undefined>(undefined);

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (value: Account[] | undefined) => {
    console.log(value);
    
    if (!value) {
        account.value = undefined;
        return;
    }
    account.value = value[0];
    
})

function Login() {
    eventBusEmit(Events.LOGIN)
}

async function logout() {
    eventBusEmit(Events.LOGOUT)
}


function createPost() {
    eventBusEmit(Events.CREATE_POST)
}
function openSearchModal() {
    eventBusEmit(Events.OPEN_SEARCH_MODAL)
}
</script>