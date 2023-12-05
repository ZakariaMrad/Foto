<template>
      <v-card-title class="text-center">{{ props.title }}</v-card-title>
        <v-container fluid>
        <v-row>
            <v-col v-for="item in props.items" cols="12" md="4">
            <v-card class="p-1">
                <h5 class="text-center mb-0">
                {{ 'idFoto' in item ? item.name : item.title  }}
                </h5>
                <v-img v-if="'idFoto' in item" :src="item.path" aspect-ratio="1" 
                :style="{filter: 'saturate(' + item.saturation +'%) contrast(' + item.contrast +'%) brightness(' + item.exposition +'%)'}"
                            />
                <v-img v-if="'idAlbum' in item" :src="item.fotos[0].path" aspect-ratio="1" 
                :style="{filter: 'saturate(' + item.fotos[0].saturation +'%) contrast(' + item.fotos[0].contrast +'%) brightness(' + item.fotos[0].exposition +'%)'}"
                            />
                <small class="text-muted text-center d-block">
                    {{ 'idFoto' in item ? (new Date(item.uploadDate!.date).getDate() + '-' +
                                           new Date(item.uploadDate!.date).getMonth() + '-' + 
                                           new Date(item.uploadDate!.date).getFullYear()) 
                                           : (new Date(item.creationDate.date).getDate() + '-' +
                                           new Date(item.creationDate.date).getMonth() + '-' + 
                                           new Date(item.creationDate.date).getFullYear())  }}
                </small>
            </v-card>
            </v-col>
        </v-row>
        </v-container>
  </template>
  
  <script setup lang="ts">
  import Foto from '../models/Foto';
  import Album from '../models/Album';
  import { onMounted, watch } from 'vue';

  const props = defineProps<{ items: (Foto[] | Album[]), title?: string }>()

  onMounted(() => {
    console.log("MOUNTED");
    props.items.reverse();
  });

  watch(() => (props.items), (value: (Foto[] | Album[])) => {
    value.reverse();

  });

  </script>
  
  <style scoped>

  .max-height-60 {
    max-height: 40vh;
    overflow-y: auto;
  }
  </style>