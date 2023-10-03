<template>
    <v-responsive aspect-ratio="1">
        <div class="card">
            <span :class="checkHover('a')" @mouseover="handleHover('a', true)" @mouseleave="handleHover('a', false)">‎
            </span>
            <span :class="checkHover('b')" @mouseover="handleHover('b', true)" @mouseleave="handleHover('b', false)">‎
            </span>
            <span :class="checkHover('c')" @mouseover="handleHover('c', true)" @mouseleave="handleHover('c', false)">‎
            </span>
            <span :class="checkHover('d')" @mouseover="handleHover('d', true)" @mouseleave="handleHover('d', false)">‎
            </span>
            <span :class="checkHover('e')" @mouseover="handleHover('e', true)" @mouseleave="handleHover('e', false)">‎
            </span>
            <span :class="checkHover('f')" @mouseover="handleHover('f', true)" @mouseleave="handleHover('f', false)">‎
            </span>
            <span :class="checkHover('g')" @mouseover="handleHover('g', true)" @mouseleave="handleHover('g', false)">‎
            </span>
            <span :class="checkHover('h')" @mouseover="handleHover('h', true)" @mouseleave="handleHover('h', false)">‎
            </span>
            <span :class="checkHover('i')" @mouseover="handleHover('i', true)" @mouseleave="handleHover('i', false)">‎
            </span>
        </div>
        <!-- <v-card :color="isHovering ? 'pink-lighten-2' : ''" height="100%" class="myclass" variant="outlined"
            @mouseover="() => isHovering = true" @mouseleave="() => isHovering = false">
            {{ props.i }} | {{ props.j  }}
        </v-card>
        <v-img :src="props.fotos?.[i*props.width+j].path" aspect-ratio="1" /> -->
    </v-responsive>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{ position: { x: number, y: number }, isHovered: boolean }>()
const hoveredIds = ref<string[]>([])
const emit = defineEmits<{ (event: 'hoveredPosition', changesToPositions: { v: number, w: number }, hoevering: boolean): void }>()


function checkHover(id: string) {
    if (props.isHovered) return 'hovered';
    return hoveredIds.value.includes(id) ? 'hovered' : '';
}


function handleHover(id: string, hovering: boolean) {
    hoveredIds.value = [];
    if (!hovering) {
        emit('hoveredPosition', { v: 0, w: 0 }, hovering)
        return;
    }
    hoveredIds.value = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i'];
    console.log(hoveredIds.value);
    switch (id) {
        case 'a':
            emit('hoveredPosition', { v: -1, w: -1 }, hovering)
            break;
        case 'b':
            emit('hoveredPosition', { v: 0, w: -1 }, hovering)
            break;
        case 'c':
            emit('hoveredPosition', { v: 1, w: -1 }, hovering)
            break;
        case 'd':
            emit('hoveredPosition', { v: -1, w: 0 }, hovering)
            break;
        case 'e':
            emit('hoveredPosition', { v: 0, w: 0 }, hovering)
            break;
        case 'f':
            emit('hoveredPosition', { v: 1, w: 0 }, hovering)
            break;
        case 'g':
            emit('hoveredPosition', { v: -1, w: 1 }, hovering)
            break;
        case 'h':
            emit('hoveredPosition', { v: 0, w: 1 }, hovering)
            break;
        case 'i':
            emit('hoveredPosition', { v: 1, w: 1 }, hovering)
            break;
    }

}

</script>

<style scoped>
.card {
    display: grid;
    grid-template-columns: 8% 84% 8%;
    grid-template-rows: 32% 336% 32%;
    gap: 0px 0px;
    grid-template-areas:
        ". . ."
        ". . ."
        ". . .";
        border: none;
}

.card span {
    margin: 0;
    padding: 0;
    border: 1px solid rgb(180, 178, 178);
}

.card span:hover {
    background-color: rgb(184, 182, 182);
}

.hovered {
    background-color: rgb(214, 212, 212);
}
</style>