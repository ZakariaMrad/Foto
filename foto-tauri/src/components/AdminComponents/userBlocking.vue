<template>
    <NewUserBlocking v-if="blocking" @done="toggleBlocking" />
    <div v-else>
        <v-row>
            <v-col>
                <h4>Liste d'utilisateurs bloqués</h4>
                <BlockedUserList  />

                <v-btn class="mt-1" color="blue" @click="toggleBlocking">Tous les utilisateurs</v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import NewUserBlocking from './newUserBlocking.vue';
import BlockedUserList from './blockedUserList.vue';

const blocking = ref<boolean>(false);

function toggleBlocking() {
    console.log('toggle');

    blocking.value = !blocking.value;
}
</script>

<style scoped></style>