<template>
  <v-dialog width="50%" v-model="props.activate">
    <v-card>
      <v-card-text >
          <Login v-if="isLogin" @isActivated="() => closeDialog()" @loggedIn="() => sendLoggedIn()" @isRegister="toggleLogin()" />
          <Register v-else @isActivated="() => closeDialog()" @loggedIn="() => sendLoggedIn()" @isRegister="toggleLogin()" />
      </v-card-text>
    </v-card>

  </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Login from './Login.vue'
import Register from './Register.vue'

const isLogin = ref(true)
const props = defineProps({
  activate: Boolean
})
const emit = defineEmits(['isActivated', 'loggedIn'])

function closeDialog() {
  emit('isActivated', false);
}
function sendLoggedIn() {
  emit('loggedIn', true);
}
function toggleLogin() {
  isLogin.value = !isLogin.value;
}

</script>

<style scoped></style>