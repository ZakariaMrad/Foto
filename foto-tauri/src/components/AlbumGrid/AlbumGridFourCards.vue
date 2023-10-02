<template>
    <v-card v-if="props.hidden == false" :color="isHovering || isHovered ? 'secondary' : ''" class="myclass"
        variant="outlined" @mouseover="() => handleHover(true)" @mouseleave="() => handleHover(false)">
    </v-card>

    <!-- <v-img :src="props.fotos?.[i*props.width+j].path" aspect-ratio="1" /> -->
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{ id1: number | undefined, id2: number | undefined, id3: number | undefined, id4: number | undefined, hidden: boolean | undefined, isHovered: boolean }>()
const isHovering = ref<boolean>(false)
const ids=[props.id1,props.id2,props.id3,props.id4]
const emit = defineEmits<{
    (event: 'hoveredList', ids: (number|undefined)[]): void
}>()

function handleHover(isHovered: boolean) {
    isHovering.value = isHovered
    emit('hoveredList', isHovered?ids:[])
}

</script>

<style scoped>
.myclass.v-card {
    border-width: 3px;
}
</style>