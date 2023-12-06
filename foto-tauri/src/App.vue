<template>
  <RouterView />
  <PostModal :key="v1()" :idPost="idPost" :activate="activatePostModal" @close-dialog="closePostModal()"/>
  <LoginRegister :key="v1()" :activate="activateLogin" @closeDialog="(val: boolean) => closeLoginRegisterDialog(val)" />
  <CreatePost :key="v1()" :activate="activateCreatePost" @closeDialog="() => activateCreatePost = false" />
  <Search :key="v1()" :activate="activateSearch" @closeDialog="() => closeSearchDialog()" />
  <EditPicture :activate="activateEdit" @close-dialog="() => closeEditDialog()" :editedPicture="editedPicture"/>
  <CreateAlbum :key="v1()" :activate="activateCreateAlbum" @closeDialog="() => activateCreateAlbum = false" />
  <ModifyProfile :key="v1()" :activate="activateModifyProfile" @closeDialog="() => closeModifyProfileDialog()" />
  <DeleteFollow :key="v1()" :activate="activateUnfollowModal" @close-dialog="() => closeUnfollowModal()" />
  <Admin :key="v1()" :activate="activateAdmin" @close-dialog="closeAdminPanel()"/>
  <Comments :key="v1()" :activate="activateComments" @close-dialog="closeComments()" :idPost="postComments"/>
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
import EditedPicture from './models/EditedPicture';
import PostModal from './components/modals/PostModal.vue';

import DeleteFollow from './components/modals/DeleteFollow.vue';

import Comments from './components/modals/Comments.vue';

import {v1} from 'uuid'; 
import Account from './models/Account';

const activateLogin = ref<boolean>(false);
const activateCreatePost = ref<boolean>(false);
const activateCreateAlbum = ref<boolean>(false);
const activateSearch = ref<boolean>(false);
const activateEdit = ref<boolean>(false);
const editedPicture= ref<EditedPicture>();
const activateModifyProfile = ref<boolean>(false);
const activateAdmin = ref<boolean>(false);
const activatePostModal = ref<boolean>(false);

const activateUnfollowModal = ref<boolean>(false);

const activateComments = ref<boolean>(false);

const idPost = ref<number | undefined>();
const idAccount = ref<number>();
const postComments = ref<number>();
const connectedAccount = ref<Account>();

const { bus, eventBusEmit } = EventsBus();

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account: Account[] | undefined) => {
    if (!account)
        return;

    connectedAccount.value = account[0];
})


watch(() => bus.value.get(Events.LOGIN), () => {
  activateLogin.value = true;  
})

watch(() => bus.value.get(Events.LOGOUT), () => {
  Logout();
})

watch(() => bus.value.get(Events.OPEN_COMMENTS), (value: number[]) => {
    activateComments.value = true;
    postComments.value = value[0];
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
  if (idAccount.value === connectedAccount.value?.idAccount){
    router.push({name :'profil'});
  } else {
    // activateOthersProfile.value = true;
    router.push({name :'otherUserProfile', params: {idAccount: idAccount.value}});
  }
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

watch(() => bus.value.get(Events.OPEN_UNFOLLOW_MODAL), () => {
  activateUnfollowModal.value = true;
})

watch(() => bus.value.get(Events.OPEN_EDIT_MODAL), (value: EditedPicture[]) => {
    activateEdit.value = true;
    editedPicture.value = value[0];
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

function closeComments() {
    activateComments.value = false;
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

function closeUnfollowModal(){
  activateUnfollowModal.value = false;
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
