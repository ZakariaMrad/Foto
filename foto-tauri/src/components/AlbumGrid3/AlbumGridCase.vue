<template>
    <v-responsive aspect-ratio="1">

        <v-card height="100%" :class="determineClass()" variant="outlined" @mousemove="handleCardEnter"
            @mouseleave="handleCardLeave">
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

function handleCardEnter(event: MouseEvent) {
    // Determine which side of the card is being hovered
    const card = event.target;
    if (!(card instanceof HTMLElement)) return;
    const rect = card.getBoundingClientRect();
    const { clientX, clientY } = event;

    const isTop = clientY - rect.top <= 20;
    const isRight = rect.right - clientX <= 20;
    const isBottom = rect.bottom - clientY <= 20;
    const isLeft = clientX - rect.left <= 20;
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
.top {
    border-top-width: 5px;
}
.left {
    border-left-width: 5px;
}
.right {
    border-right-width: 5px;
}
.bottom {
    border-bottom-width: 5px;
}
.center {
    border-width: 5px;
}
</style>