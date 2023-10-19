<template>
    <v-responsive aspect-ratio="1">
        <v-card height="100%" :class="determineClass()" class="card" variant="outlined" @drag="handleCardEnter($event)"
            @dragend="handleCardLeave"
            @dragenter="handleCardEnter"
            @dragleave="handleCardLeave"
            @drop="console.log('drop')"
            draggable="true">
        </v-card>
    </v-responsive>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import HoveredCard from '../../models/HoveredCard';

const props = defineProps<{ position: { x: number, y: number }, hoveredSidesOut: HoveredCard }>()
const emit = defineEmits<{ (event: 'hovered', hoveredSides: HoveredCard): void }>()
const hoveredSides = ref<HoveredCard>()

function determineClass(): string {
    // console.log(props.hoveredSidesOut);
    switch (true) {
        case hoveredSides.value?.topLeft:
        case props.hoveredSidesOut.topLeft:
            return 'bottom right';
        case hoveredSides.value?.topRight:
        case props.hoveredSidesOut.topRight:
            return 'bottom left';
        case hoveredSides.value?.bottomLeft:
        case props.hoveredSidesOut.bottomLeft:
            return 'top right';
        case hoveredSides.value?.bottomRight:
        case props.hoveredSidesOut.bottomRight:
            return 'top left';
        case hoveredSides.value?.top:
        case props.hoveredSidesOut.top:
            return 'right bottom left';
        case hoveredSides.value?.right:
        case props.hoveredSidesOut.right:
            return 'left top bottom';
        case hoveredSides.value?.bottom:
        case props.hoveredSidesOut.bottom:
            return 'right top left';
        case hoveredSides.value?.left:
        case props.hoveredSidesOut.left:
            return 'right top bottom';
        case hoveredSides.value?.center:
        case props.hoveredSidesOut.center:
            return 'center';
    }
    return '';
}

function handleCardEnter(event: DragEvent) {
    console.log('card enter', event);
    event.preventDefault();
    
    // Determine which side of the card is being hovered
    const card = event.target;
    if (!(card instanceof HTMLElement)) return;
    const rect = card.getBoundingClientRect();
    const target = event.target as HTMLElement;
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    const w = target.offsetWidth;
    const h = target.offsetHeight;
    const isTop = y < h / 4;
    const isRight = x > w - w / 4;
    const isBottom = y > h - h / 4;
    const isLeft = x < w / 4;
    const isTopLeft = isTop && isLeft;
    const isTopRight = isTop && isRight;
    const isBottomLeft = isBottom && isLeft;
    const isBottomRight = isBottom && isRight;
    const isCenter = !isTop && !isRight && !isBottom && !isLeft;

    // Update hover state
    hoveredSides.value = new HoveredCard(isTop, isBottom, isLeft, isRight, isTopLeft, isTopRight, isBottomLeft, isBottomRight, isCenter)
    emit('hovered', hoveredSides.value);
}
function handleCardLeave() {
    // Reset hover state when leaving the card
    hoveredSides.value = HoveredCard.emptyHoveredCard();
    emit('hovered', hoveredSides.value);
}
</script>

<style scoped>
.card {
    border-width: 3px;
}

.top {
    border-top-color: rgb(28, 255, 225);
}

.left {
    border-left-color: rgb(28, 255, 225);
}

.right {
    border-right-color: rgb(28, 255, 225);
}

.bottom {
    border-bottom-color: rgb(28, 255, 225);
}

.center {
    border-color: rgb(28, 255, 225);
}</style>