<template>
  <RouterView />
  <LoginRegister :activate="activateLogin" @closeDialog="(val: boolean) => closeLoginRegisterDialog(val)" />
  <CreatePost :activate="activateCreatePost" @closeDialog="() => activateCreatePost = false" />
  <Search :activate="activateSearch" @closeDialog="() => closeSearchDialog()" />
  <CreateAlbum :activate="activateCreateAlbum" @closeDialog="() => activateCreateAlbum = false" />
  <ModifyProfile :activate="activateModifyProfile" @closeDialog="() => closeModifyProfileDialog()" />
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { EventsBus, Events } from './core/EventBus';
import LoginRegister from './components/modals/LoginRegister.vue';
import AccountRepository from './repositories/AccountRepository';
import CreatePost from './components/modals/CreatePost.vue';
import Search from './components/modals/Search.vue';
import CreateAlbum from './components/modals/CreateAlbum.vue';
import ModifyProfile from './components/modals/ModifyProfile.vue'

const activateLogin = ref<boolean>(false);
const activateCreatePost = ref<boolean>(false);
const activateCreateAlbum = ref<boolean>(false);
const activateSearch = ref<boolean>(false);

const activateModifyProfile = ref<boolean>(false);
const { bus, eventBusEmit } = EventsBus();

watch(() => bus.value.get(Events.LOGIN), () => {
  activateLogin.value = true;
})

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account) => {
  console.log(account);
  
})

watch(() => bus.value.get(Events.LOGOUT), () => {
  Logout();
})

watch(() => bus.value.get(Events.CREATE_POST), () => {
  activateCreatePost.value = true;
})
watch(() => bus.value.get(Events.OPEN_SEARCH_MODAL), () => {

  activateSearch.value = true;
})
watch(()=> bus.value.get(Events.CREATE_ALBUM), () => {
  activateCreateAlbum.value = true;
})
watch(()=> bus.value.get(Events.RELOAD_CONNECTED_ACCOUNT), () => {
  getAccount();
})

watch(() => bus.value.get(Events.OPEN_MODIFY_PROFILE_MODAL), () => {

  activateModifyProfile.value = true;
})

onMounted(async () => {
  let isConnected = await AccountRepository.isConnected();
  if (!isConnected) return;
  await getAccount();
})

function closeSearchDialog() {
  activateSearch.value = false;
}

function closeModifyProfileDialog() {
  activateModifyProfile.value = false;
}

async function closeLoginRegisterDialog(val: boolean) {
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
