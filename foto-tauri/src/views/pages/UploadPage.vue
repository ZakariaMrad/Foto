<template>
  <DefaultLayout>
    <div class="d-flex align-center flex-column mt-3">
      <h1 class="text-h2 text-bold">Téléversement</h1>
    </div>
    <div class="w-50 mx-auto image-input">
      <v-file-input label="Parcourir..." 
                    variant="solo" 
                    accept="image/png, image/jpeg, image/webp" 
                    multiple 
                    chips
                    prepend-icon="mdi-camera"
                    v-model="files"
                    @update:model-value="printFiles">
      </v-file-input>
    </div>


    <v-card v-for="(file, index) in files">
      <v-img
          height="200"
          :src="'https://picsum.photos/300/200?random=' + index"
          cover
          @click="removeFromFiles(file)">
      </v-img>
    </v-card>
    
  </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '../layouts/DefaultLayout.vue';
import { ref } from 'vue';

const files = ref([]);

function printFiles() {
  console.log(files.value);
}

function removeFromFiles(file: File)
{
  files.value = files.value.filter((f) => f !== file);
}

</script>

<style scoped>

.image-input {
  margin-top: 200px;
}

</style>