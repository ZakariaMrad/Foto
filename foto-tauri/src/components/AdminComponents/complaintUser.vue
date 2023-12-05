<template>
    <v-card width="100%" class="ma-4">
        <v-card-title>{{ role }}</v-card-title>
        <v-card-text>
            <p>({{ account?.name }} - {{ account?.email }})</p>
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

</script>

<style scoped></style>