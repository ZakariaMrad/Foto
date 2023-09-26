<template>
  <RouterView />
  <LoginRegister :activate="activateLogin" @closeDialog="(val: boolean) => closeLoginDialog(val)" />
  <CreatePost :activate="activateCreatePost" @closeDialog="() => activateCreatePost= false" />
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { EventsBus, Events } from './core/EventBus';
import LoginRegister from './components/modals/LoginRegister.vue';
import AccountRepository from './repositories/AccountRepository';
import CreatePost from './components/modals/CreatePost.vue';

const activateLogin = ref<boolean>(false);
const activateCreatePost = ref<boolean>(false);
const { bus, eventBusEmit } = EventsBus();

watch(() => bus.value.get(Events.LOGIN), () => {
  activateLogin.value = true;
})

watch(() => bus.value.get(Events.LOGOUT), () => {
  Logout();
})

watch(() => bus.value.get(Events.CREATE_POST), () => {
  activateCreatePost.value = true;
})

onMounted(async () => {
  let isConnected = await AccountRepository.isConnected();
  if (!isConnected) return;
  await getAccount();

})

async function closeLoginDialog(val: boolean) {
  activateLogin.value = false;
  if (!val) return;
  await getAccount();
}
async function Logout() {
  AccountRepository.logout();
  eventBusEmit(Events.CONNECTED_ACCOUNT, undefined)
}
async function getAccount() {
  let apiResponse = await AccountRepository.getAccount();
  if (!apiResponse.success) return;
  let account = apiResponse.data;
  eventBusEmit(Events.CONNECTED_ACCOUNT, account)
}
</script>

<style scoped></style>
