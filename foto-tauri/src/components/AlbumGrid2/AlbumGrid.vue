<template>
    <v-container class="mb-1">
        <v-row>

            <v-col cols="7">
                <h4 class="text-center">Grille</h4>
                <v-row v-for="(_, y) in 4" no-gutters>
                    <v-col class="ma-0 pa-0" v-for="(_, x) in 4" cols="3">
                        <AlbumGridCase :position="{ x, y }" :isHovered="checkIfHovered({ x, y })"
                            @hoveredPosition="(changesToPositions, hovering) => hoverPosition({ x, y }, changesToPositions, hovering)" />
                    </v-col>
                </v-row>
            </v-col>
            <v-col cols="5">
                <h4 class="text-center">Fotos</h4>
                <FotoPicker :items="fotos" :itemSize="3" :multiple="true"  />
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

const hoveredPositon = ref<{ x: number, y: number }[]>([]);
const fotos = ref<Foto[]>([]);

onMounted(async () => {
    let apiResult = await FotoRepository.getFotos();
    if (!apiResult.success) return;
    fotos.value = apiResult.data;
})

function hoverPosition(ownPosition: { x: number, y: number }, changesToPositions: { v: number, w: number }, hovering: boolean) {

    console.log(hoveredPositon.value);
    hoveredPositon.value = [];
    if (!hovering) return;
    hoveredPositon.value.push({ x: ownPosition.x, y: ownPosition.y });
    hoveredPositon.value.push({ x: ownPosition.x + changesToPositions.v, y: ownPosition.y });
    hoveredPositon.value.push({ x: ownPosition.x, y: ownPosition.y + changesToPositions.w });
    hoveredPositon.value.push({ x: ownPosition.x + changesToPositions.v, y: ownPosition.y + changesToPositions.w });
}

function checkIfHovered(position: { x: number, y: number }) {
    return hoveredPositon.value.some((pos) => pos.x == position.x && pos.y == position.y);
}
</script>

<style scoped></style>