<template>
  <v-card class="max-height-60 my-3">
    <v-card-title class="text-center">{{ props.title }}</v-card-title>
    <v-window>
      <v-window-item v-for="n in 3" :key="n" :value="n">
        <v-container fluid>
          <v-row>
            <v-col v-for="foto in props.fotos" cols="12" md="4">
              <v-card class="p-1" @click="select(foto)">
                <h5 class="text-center mb-0">
                  {{ foto.name }}
                </h5>
                <v-img :src="foto.path" aspect-ratio="1"
                  :style="{ filter: 'saturate(' + foto.saturation + '%) contrast(' + foto.contrast + '%) brightness(' + foto.exposition + '%)' }" />
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-window-item>
    </v-window>
  </v-card>
</template>

<script setup lang="ts">
import {   watch } from 'vue';
import Foto from '../models/Foto';
import Album from '../models/Album';
const props = defineProps<{ fotos: Foto[], title?: string }>()

const emit = defineEmits<{ (event: 'selectedItem', foto: Foto): void }>()


watch(() => (props.fotos), (value: Foto[] | Album[]) => {
  value.reverse();
});

function select(foto:Foto) {
  emit('selectedItem', foto);
}
</script>

<style scoped>
.max-height-60 {
  max-height: 40vh;
  overflow-y: auto;
}
</style>