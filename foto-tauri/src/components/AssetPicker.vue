<template>
    <v-card class="max-height-60 my-3">
      <v-window>
        <v-window-item v-for="n in 3" :key="n" :value="n">
          <v-container fluid>
            <v-row>
              <v-col v-for="(item, i) in props.items" cols="12" md="4">
                <v-card class="p-1" v-bind:class="{ 'bg-blue-darken-4': activeItemsId.includes(i) }" @click="setActive(i)">
                  <v-img :src="item.path" aspect-ratio="1" />
                </v-card>
              </v-col>
            </v-row>
          </v-container>
        </v-window-item>
      </v-window>
    </v-card>
  </template>

<script setup lang="ts">
import { onMounted, ref, } from 'vue';
import Foto from '../models/Foto';
const props = defineProps<{ items: Foto[], itemSize: number, multiple: boolean }>()

const emit = defineEmits(['itemsSelected'])
const activeItemsId = ref<number[]>([]);
let activeItems: Foto[] = [];
onMounted(() => {
    console.log(props.items);
})

function setActive(index: number) {
    if (!props.multiple) {
        activeItemsId.value = [];
        activeItemsId.value.push(index);
    } else {
        if (activeItemsId.value.includes(index)) {
            activeItemsId.value.splice(activeItemsId.value.indexOf(index), 1);
        } else {
            activeItemsId.value.push(index);
        }
    }
    activeItems = [];
    activeItemsId.value.forEach((id) => {
        activeItems.push(props.items[id]);
    })
    emit('itemsSelected', activeItems)
}
</script>

<style scoped>
.max-height-60 {
  max-height: 40vh;
  overflow-y: auto;
}
</style>