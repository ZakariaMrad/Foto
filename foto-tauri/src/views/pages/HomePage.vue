<template>
    <DefaultLayout>
        accueil
        <v-btn text="test account" color="indigo-darken-3" variant="outlined" @click="testAccount"/>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '../layouts/DefaultLayout.vue'

import AccountRepository from '../../repositories/AccountRepository';

async function testAccount() {
    let account = await AccountRepository.getAccount()
    console.log('account', account);
    
}
</script>

<style scoped></style>