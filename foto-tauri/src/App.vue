<template>
  <RouterView />
  <PostModal :key="v1()" :idPost="idPost" :activate="activatePostModal" @close-dialog="closePostModal()"/>
  <LoginRegister :key="v1()" :activate="activateLogin" @closeDialog="(val: boolean) => closeLoginRegisterDialog(val)" />
  <CreatePost :key="v1()" :activate="activateCreatePost" @closeDialog="() => activateCreatePost = false" />
  <Search :key="v1()" :activate="activateSearch" @closeDialog="() => closeSearchDialog()" />
  <EditPicture :key="v1()" :activate="activateEdit" @close-dialog="() => closeEditDialog()" :img-src="editImgSrc"/>
  <CreateAlbum :key="v1()" :activate="activateCreateAlbum" @closeDialog="() => activateCreateAlbum = false" />
  <ModifyProfile :key="v1()" :activate="activateModifyProfile" @closeDialog="() => closeModifyProfileDialog()" />
  <Admin :key="v1()" :activate="activateAdmin" @close-dialog="closeAdminPanel()"/>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import { EventsBus, Events } from './core/EventBus';
import LoginRegister from './components/modals/LoginRegister.vue';
import AccountRepository from './repositories/AccountRepository';
import CreatePost from './components/modals/CreatePost.vue';
import Search from './components/modals/Search.vue';
import EditPicture from './components/modals/EditPicture.vue';
import CreateAlbum from './components/modals/CreateAlbum.vue';
import ModifyProfile from './components/modals/ModifyProfile.vue'
import Admin from './components/modals/Admin.vue';
import router from './router';
import PostModal from './components/modals/PostModal.vue';
import {v1} from 'uuid'; 

const activateLogin = ref<boolean>(false);
const activateCreatePost = ref<boolean>(false);
const activateCreateAlbum = ref<boolean>(false);
const activateSearch = ref<boolean>(false);
const activateEdit = ref<boolean>(false);
const editImgSrc = ref<string>("");
const activateModifyProfile = ref<boolean>(false);
const activateAdmin = ref<boolean>(false);
const activatePostModal = ref<boolean>(false);
const idPost = ref<number | undefined>();
const idAccount = ref<number>();

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

watch(() => bus.value.get(Events.OPEN_SEARCH_MODAL), () => {
  activateSearch.value = true;
})
watch(()=> bus.value.get(Events.CREATE_ALBUM), () => {
  activateCreateAlbum.value = true;
})
watch(()=> bus.value.get(Events.RELOAD_CONNECTED_ACCOUNT), () => {
  getAccount();
})

watch(() => bus.value.get(Events.OPEN_USER_PROFILE ), (value) => {
  idAccount.value = value[0];
  // activateOthersProfile.value = true;
  router.push({name :'otherUserProfile', params: {idAccount: idAccount.value}});
})

watch(() => bus.value.get(Events.OPEN_POST_MODAL), (value) => {
  idPost.value = value[0];  
  activatePostModal.value = true;
})

watch(() => bus.value.get(Events.OPEN_MODIFY_PROFILE_MODAL), () => {

  activateModifyProfile.value = true;
})

watch(() => bus.value.get(Events.OPEN_ADMIN_PANEL), () => {
  activateAdmin.value = true;
})

watch(() => bus.value.get(Events.OPEN_EDIT_MODAL), (value: string[]) => {
    activateEdit.value = true;
    editImgSrc.value = value[0];
});

onMounted(async () => {
  let isConnected = await AccountRepository.isConnected();
  if (!isConnected) return;
  await getAccount();
})

function closeEditDialog() {
    activateEdit.value = false;
}
function closeSearchDialog() {
  activateSearch.value = false;
}
function closeModifyProfileDialog() {
  activateModifyProfile.value = false;
}
function closeAdminPanel() {
  activateAdmin.value = false;
}
async function closeLoginRegisterDialog(val: boolean) {
  activateLogin.value = false;
  if (!val) return;
  await getAccount();
}

function closePostModal(){
  activatePostModal.value = false;
}

async function Logout() {
  AccountRepository.logout();
  eventBusEmit(Events.CONNECTED_ACCOUNT, undefined)
  //change route to home page
  router.push({ name: 'home' })
  console.log();
}
async function getAccount() {
  let apiResponse = await AccountRepository.getAccount();
  // console.log(apiResponse);
  
  if (!apiResponse.success) return;
  let account = apiResponse.data;
  // console.log(account);
  
  eventBusEmit(Events.CONNECTED_ACCOUNT, account)
}
</script>

<style scoped></style>
