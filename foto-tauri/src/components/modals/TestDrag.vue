<template>
    <v-dialog v-model="props.activate" width="90%">
        <v-card>
            <v-responsive aspect-ratio="3">
                <v-card-title>Test Drag</v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col>
                            <VueDraggableNext style="min-height: 10vh;" class="dragArea list-group w-full bg-primary"
                                @change="(ev: any) => console.log(ev)" @drop="[list1, list2] = onDrop(list1, list2)">
                                <div class="list-group-item bg-gray-300 m-1 p-3 rounded-md text-center"
                                    v-for="element in list1" :key="element.name" :id="element.id.toString()"
                                    @dragstart="onDragStart">
                                    {{ element.name }}
                                </div>
                            </VueDraggableNext>
                        </v-col>
                        <v-col>
                            <VueDraggableNext style="min-height: 10vh;" class="dragArea list-group w-full bg-primary"
                                @change="(ev: any) => console.log(ev)" @drop="[list2, list1]= onDrop(list2, list1)">
                                <div class="list-group-item bg-gray-300 m-1 p-3 rounded-md text-center"
                                    v-for="element in list2" :key="element.name" :id="element.id.toString()"
                                    @dragstart="onDragStart">
                                    {{ element.name }}
                                </div>
                            </VueDraggableNext>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-responsive>
        </v-card>
    </v-dialog>
</template>
    
<script setup lang="ts">
import { ref } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
const props = defineProps<{ activate: boolean }>()

const list1 = ref<{ name: string; id: number; }[]>([
    { name: 'John', id: 1 },
    { name: 'Joao', id: 2 },
    { name: 'Jean', id: 3 },
    { name: 'Gerard', id: 4 },
]);
const list2 = ref<{ name: string; id: number; }[]>([
]);
const sharedId = ref(0);

function onDragStart(event: DragEvent) {
    const target = event.target as HTMLElement;
    const itemId = target.id;
    sharedId.value = parseInt(itemId);
    console.log('dragstart', itemId);
}
function onDrop(myList: { name: string; id: number; }[], senderList: { name: string; id: number; }[]) {
    console.log('drop');
    if (!sharedId.value) return;

    const item = senderList.find((i) => i.id === sharedId.value);
    if (!item) return [myList, senderList]
    console.log(senderList);

    senderList = senderList.filter((i) => i.id !== sharedId.value);
    console.log(senderList);
    myList.push(item);
    return [myList, senderList];
}



</script>