<template>
  <v-card class="max-height-60 my-3">
    <v-card-title class="text-center">{{ props.title }}</v-card-title>
    <v-window>
      <v-window-item v-for="n in 3" :key="n" :value="n">
        <v-container fluid>
          <v-row>
            <v-col v-for="(item, i) in props.items" cols="12" md="4">
              <v-card class="p-1" @click="setActive(i)">
                <h5 class="text-center mb-0">
                  {{ 'idFoto' in item ? item.name : item.title }}
                </h5>
                <v-img v-if="'idFoto' in item" :src="item.path" aspect-ratio="1"
                  :style="{ filter: 'saturate(' + item.saturation + '%) contrast(' + item.contrast + '%) brightness(' + item.exposition + '%)' }" />
                <v-img v-if="'idAlbum' in item" :src="item.fotos[0].path" aspect-ratio="1"
                  :style="{ filter: 'saturate(' + item.fotos[0].saturation + '%) contrast(' + item.fotos[0].contrast + '%) brightness(' + item.fotos[0].exposition + '%)' }" />
                <small class="text-muted text-center d-block">
                  {{ 'idFoto' in item ? (new Date(item.uploadDate!.date).getDate() + '-' +
                    new Date(item.uploadDate!.date).getMonth() + '-' +
                    new Date(item.uploadDate!.date).getFullYear())
                    : (new Date(item.creationDate.date).getDate() + '-' +
                      new Date(item.creationDate.date).getMonth() + '-' +
                      new Date(item.creationDate.date).getFullYear()) }}
                </small>
              </v-card>
            </v-col>
          </v-row>
        </v-container>
      </v-window-item>
    </v-window>
  </v-card>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import Foto from '../models/Foto';
import Album from '../models/Album';
const props = defineProps<{ items: (Foto[] | Album[]), itemSize: number, multiple: boolean, unselectable?: boolean, title?: string }>()

const emit = defineEmits<{ (event: 'itemsSelected', items: typeof props.items[number][]): void }>()
const activeItemsId = ref<number[]>([]);
let activeItems: (Foto | Album)[] = [];
onMounted(() => {
  console.log('assetPicker', props.items);
})

watch(() => (props.items), (value: Foto[] | Album[]) => {
  value.reverse();
});

function setActive(index: number) {
  if (props.unselectable) return;
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