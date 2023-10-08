<template>
  <div>
    <h1>Draggable</h1>
    <vue-draggable-next v-model="items" :item-key="'id'" @start="dragStart" @end="dragEnd">
      <div v-for="item in items" :key="item.id" @mouseover="selectItem(item.id)">
        <v-img :src="item.path" @click="selectItem(item.id)" />
      </div>
    </vue-draggable-next>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
const items = ref([
  { id: 1, path: 'https://cdn.pixabay.com/photo/2016/07/15/16/50/man-1519667_1280.jpg'},
  { id: 2, path: 'https://cdn.pixabay.com/photo/2019/09/06/06/44/kitesurfing-4455668_1280.jpg'},
  { id: 3, path: 'https://cdn.pixabay.com/photo/2014/08/03/21/19/bank-409368_1280.jpg' },
]);

const selectionStart = ref<boolean>(false);
const selectedItem = ref<{id:number,path:string}>();


function dragStart(event:DragEvent) {
  console.log('Dragging started:', event);
  selectionStart.value = true;
}

function dragEnd(event:DragEvent) {
  console.log('Dragging ended:', event);
  selectionStart.value = false;
}

function selectItem(id:number) {
  if (selectionStart.value) return;
  selectedItem.value= items.value.find((item) => item.id == id);
  console.log(selectedItem.value);
}
</script>

<style scoped></style>