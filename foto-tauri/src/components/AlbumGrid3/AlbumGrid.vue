<template>
    <v-container class="mb-1">
        <v-row>

            <v-col cols="7">
                <h4 class="text-center">Grille</h4>
                <v-row v-for="(_, y) in 4" no-gutters>
                    <v-col class="ma-0 pa-0" v-for="(_, x) in 3">
                        <AlbumGridCase :position="{ x, y }" :hoveredSidesOut="checkHoveredSides({ x, y })"
                            @hovered="(hoveredSides) => hoverCards({ x, y }, hoveredSides)" />
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="5">
                <h4 class="text-center">Fotos</h4>
                <FotoPicker :items="fotos" :itemSize="3" :multiple="true" />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import AlbumGridCase from './AlbumGridCase.vue';
import FotoPicker from '../FotoPicker.vue';
import Foto from '../../models/Foto';
import FotoRepository from '../../repositories/FotoRepository';
import HoveredCard from '../../models/HoveredCard';

const fotos = ref<Foto[]>([]);
const hoveredCards = ref<{ x: number, y: number, hoveredSides: HoveredCard }[]>([]);

onMounted(async () => {
    let apiResult = await FotoRepository.getFotos();
    if (!apiResult.success) return;
    fotos.value = apiResult.data;
})

function hoverCards(ownPosition: { x: number, y: number }, HoveredCardIn: HoveredCard) {
    hoveredCards.value = [];
    console.log(HoveredCardIn);

    switch (true) {
        case HoveredCardIn.topLeft:
            hoveredCards.value = [
                { x: ownPosition.x - 1, y: ownPosition.y - 1, hoveredSides: HoveredCard.onlyBottomRight() },
                { x: ownPosition.x - 1, y: ownPosition.y, hoveredSides: HoveredCard.onlyTopRight() },
                { x: ownPosition.x, y: ownPosition.y - 1, hoveredSides: HoveredCard.onlyBottomLeft() },
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyBottomRight() },
            ];
            break;
        case HoveredCardIn.topRight:
            hoveredCards.value = [
                { x: ownPosition.x, y: ownPosition.y - 1, hoveredSides: HoveredCard.onlyBottomRight() },
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyTopRight() },
                { x: ownPosition.x + 1, y: ownPosition.y - 1, hoveredSides: HoveredCard.onlyBottomLeft() },
                { x: ownPosition.x + 1, y: ownPosition.y, hoveredSides: HoveredCard.onlyTopLeft() },
            ];
            break;
        case HoveredCardIn.bottomLeft:
            hoveredCards.value = [
                { x: ownPosition.x - 1, y: ownPosition.y, hoveredSides: HoveredCard.onlyBottomRight() },
                { x: ownPosition.x - 1, y: ownPosition.y + 1, hoveredSides: HoveredCard.onlyTopRight() },
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyBottomLeft() },
                { x: ownPosition.x, y: ownPosition.y + 1, hoveredSides: HoveredCard.onlyTopLeft() },
            ];
            break;
        case HoveredCardIn.bottomRight:
            hoveredCards.value = [
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyBottomRight() },
                { x: ownPosition.x, y: ownPosition.y + 1, hoveredSides: HoveredCard.onlyTopRight() },
                { x: ownPosition.x + 1, y: ownPosition.y, hoveredSides: HoveredCard.onlyBottomLeft() },
                { x: ownPosition.x + 1, y: ownPosition.y + 1, hoveredSides: HoveredCard.onlyTopLeft() },
            ];
            break;
        case HoveredCardIn.top:
            hoveredCards.value = [
                { x: ownPosition.x, y: ownPosition.y - 1, hoveredSides: HoveredCard.onlyBottom() },
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyTop() },
            ];
            break;
        case HoveredCardIn.right:
            hoveredCards.value = [
                { x: ownPosition.x + 1, y: ownPosition.y, hoveredSides: HoveredCard.onlyLeft() },
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyRight() },
            ];
            break;
        case HoveredCardIn.bottom:
            hoveredCards.value = [
                { x: ownPosition.x, y: ownPosition.y + 1, hoveredSides: HoveredCard.onlyTop() },
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyBottom() },
            ];
            break;
        case HoveredCardIn.left:
            hoveredCards.value = [
                { x: ownPosition.x, y: ownPosition.y, hoveredSides: HoveredCard.onlyLeft() },
                { x: ownPosition.x - 1, y: ownPosition.y, hoveredSides: HoveredCard.onlyRight() },
            ];
            break;
        default:
            break;

    }
    console.log(hoveredCards.value);

}

function checkHoveredSides(position: { x: number, y: number }): HoveredCard {
    let hoveredSides = hoveredCards.value.find(hoveredCard => hoveredCard.x === position.x && hoveredCard.y === position.y);
    if (!hoveredSides) return HoveredCard.emptyHoveredCard();
    return hoveredSides.hoveredSides;
}
</script>

<style scoped></style>