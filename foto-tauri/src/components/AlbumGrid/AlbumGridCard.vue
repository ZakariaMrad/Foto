<template>
    <v-responsive aspect-ratio="1">

        <v-card :color="isHovering || isHovered ? 'pink-lighten-2' : ''" height="100%" class="myclass" variant="outlined" @mouseover="() => isHovering = true"
            @mouseleave="() => isHovering = false">
            {{ props.id }}
        </v-card>
        <!-- <v-img :src="props.fotos?.[i*props.width+j].path" aspect-ratio="1" /> -->
    </v-responsive>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{ id: number, isHovered:boolean }>()
const isHovering = ref<boolean>(false)

</script>

<style scoped>
.myclass.v-card {
    border-width: 3px;
}
</style>