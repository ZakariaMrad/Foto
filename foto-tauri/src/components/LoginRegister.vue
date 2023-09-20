<template>
  <v-dialog width="500" v-model="props.activate">
    <v-card>
      <v-card-text v-if="isLogin">
        <h3 class="text-center">Login</h3>
        <div class="container">
          <Login @isActivated="() => closeDialog()" @loggedIn="() => sendLoggedIn()" @isRegister="toggleLogin()" />
        </div>
      </v-card-text>
      <v-card-text v-else>
        <h3 class="text-center">Register</h3>
        <div class="container">
          <Register @isActivated="() => closeDialog()" @loggedIn="() => sendLoggedIn()" @isRegister="toggleLogin()" />
        </div>
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