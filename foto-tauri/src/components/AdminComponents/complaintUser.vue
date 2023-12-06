<template>
    <v-card width="100%" class="ma-4">
        <v-card-title>{{ role }}</v-card-title>
        <v-card-text>
            <p>({{ account?.name }} - {{ account?.email }})</p>
            <p>Inscrit depuis {{ formatDate(account?.creationDate.date) }}</p>
        </v-card-text>
        <v-card-actions>
            <v-btn color="red-darken-3" @click="blockUser()">Bloquer l'utilisateur</v-btn>
        </v-card-actions>
        <BlockUserModal :account="account" :activate="activate" @closeDialog="activate = false" />
    </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Account from '../../models/Account';
import BlockUserModal from './blockUserModal.vue';
const activate = ref<boolean>(false);

defineProps({
    account: Account,
    role:String
})

function blockUser() {
    activate.value = true;
}
function formatDate(date: string | undefined) {
    if (!date) return '';
    const formattedDate = new Date(date).toLocaleDateString("fr-CA") + " à " + new Date(date).toLocaleTimeString("fr-CA") ;
    // const formattedDate = new Date(date).toLocaleDateString("fr-CA");
    return formattedDate;
}
</script>

<style scoped></style>