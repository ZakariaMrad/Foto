<template>
      <v-card-title class="text-center">{{ props.title }}</v-card-title>
        <v-container fluid>
        <v-row>
            <v-col v-for="item in props.items" cols="12" md="4">
            <v-card class="p-1">
                <h5 class="text-center mb-0">
                {{ 'idFoto' in item ? item.name : item.title  }}
                </h5>
                <v-img v-if="'idFoto' in item" :src="item.path" aspect-ratio="1" />
                <v-img v-if="'idAlbum' in item" :src="item.fotos[0].path" aspect-ratio="1" />
                <small class="text-muted text-center d-block">
                    {{ 'idFoto' in item ? (new Date(item.uploadDate.date).getDate() + '-' +
                                           new Date(item.uploadDate.date).getMonth() + '-' + 
                                           new Date(item.uploadDate.date).getFullYear()) 
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
  import { onMounted } from 'vue';

  const props = defineProps<{ items: (Foto[] | Album[]), title?: string }>()

  const date: Date = new Date();

  onMounted(() => {
    props.items.reverse();
  });

  </script>
  
  <style scoped>

  .max-height-60 {
    max-height: 40vh;
    overflow-y: auto;
  }
  </style>