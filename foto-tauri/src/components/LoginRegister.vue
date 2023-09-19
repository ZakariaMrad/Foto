<template>
  <v-dialog width="500" v-model="props.activate">
    <v-card>
      <v-card-text>
        <h3 class="text-center">Login</h3>
        <div class="container">
          <Login @isActivated="()=>closeDialog()" />
        </div>
      </v-card-text>
    </v-card>

  </v-dialog>
</template>

<script setup lang="ts">
import Login from './Login.vue'
const props = defineProps({
  activate: Boolean
})
const emit = defineEmits(['isActivated'])

function closeDialog() {
  emit('isActivated', false);
}

</script>

<style scoped></style>