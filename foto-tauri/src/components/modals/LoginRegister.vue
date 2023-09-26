<template>
  <v-dialog width="40%" v-model="props.activate">
    <v-card>
      <v-card-text >
          <Login v-if="isLogin" @close-dialog="(val:any) => closeDialog(val)" @isRegister="toggleLogin()" />
          <Register v-else @close-dialog="(val:boolean) => closeDialog(val)" @isRegister="toggleLogin()" />
      </v-card-text>
    </v-card>

  </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Login from '../Login.vue'
import Register from '../Register.vue'

const isLogin = ref(true)
const props = defineProps({
  activate: Boolean
})
const emit = defineEmits(['closeDialog'])

function closeDialog(val: boolean) {
  emit('closeDialog', val);
  console.log(val, 'val');
  
}

function toggleLogin() {
  isLogin.value = !isLogin.value;
}

</script>

<style scoped></style>