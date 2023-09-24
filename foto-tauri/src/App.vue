<script setup lang="ts">
import { onMounted } from 'vue';
import { RouterView } from 'vue-router'
import AccountRepository from './repositories/AccountRepository';
onMounted(async () => {
  let accountConnected = await AccountRepository.isConnected()
  console.log('App.vue mounted', accountConnected)
  
    AccountRepository.startConnectionFlow(test, 2000)
  })
const test = (value:boolean) => {
  console.log( 'is Connected',value)
}
</script>
  
<template>
  <RouterView />
</template>

<style scoped></style>