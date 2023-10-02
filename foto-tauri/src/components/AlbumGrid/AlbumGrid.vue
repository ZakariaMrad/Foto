<template>
    <v-container>
        <v-col cols="7">

            <v-row v-for="(_, i) of props.heigth" :key="i">
                <v-col v-for="(_, j) of props.width" class="p-0" :key="j">
                    <!-- <AlbumGridCard :id="i * props.width + j"/>
                        <AlbumGridCardVertical :id="i * props.width + j" /> -->
                    <div v-if="j == props.width - 1">
                        <div v-if="i == props.heigth - 1">
                            <div class="gridC">
                                <AlbumGridCard :id="i * props.width + j" :isHovered="hoveredIds.includes(i * props.width + j)"/>
                                <AlbumGridTwoCards :hidden="true" />
                            </div>
                        </div>
                        <div v-else>
                            <div class="gridA">
                                <AlbumGridCard :id="i * props.width + j" :isHovered="hoveredIds.includes(i * props.width + j)"/>
                                <AlbumGridTwoCards :hidden="true" />
                                <AlbumGridTwoCards :id1="i * props.width + j" :id2="(i + 1) * props.width + j"
                                    @hoveredList="(ids) => setHoveredList(ids)" />
                                <AlbumGridFourCards :hidden="true" />
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="i == props.heigth - 1">
                            <div class="gridC">
                                <AlbumGridCard :id="i * props.width + j" :isHovered="hoveredIds.includes(i * props.width + j)"/>
                                <AlbumGridTwoCards :id1="i * props.width + j" :id2="(i+1) * props.width + j"
                                    @hoveredList="(ids) => setHoveredList(ids)" />
                            </div>
                        </div>
                        <div v-else>
                            <div class="gridA">
                                <AlbumGridCard :id="i * props.width + j" :isHovered="hoveredIds.includes(i * props.width + j)"/>
                                <AlbumGridTwoCards :id1="i * props.width + j" :id2="i * props.width + j + 1"
                                    @hoveredList="(ids) => setHoveredList(ids)" />
                                <AlbumGridTwoCards :id1="i * props.width + j" :id2="(i+1) * props.width + j"
                                    @hoveredList="(ids) => setHoveredList(ids)" />
                                <AlbumGridFourCards :id1="i * props.width + j" :id2="i * props.width + j + 1"
                                    :id3="(i + 1) * props.width + j" :id4="(i + 1) * props.width + j + 1"
                                    @hoveredList="(ids) => setHoveredList(ids)" />
                            </div>
                        </div>
                    </div>

                </v-col>
            </v-row>
        </v-col>
    </v-container>
</template>

<script setup lang="ts">
import Foto from '../../models/Foto';
import AlbumGridCard from './AlbumGridCard.vue';
import AlbumGridTwoCards from './AlbumGridTwoCards.vue';
import AlbumGridFourCards from './AlbumGridFourCards.vue';
import { ref } from 'vue';

const props = defineProps<{ width: number, heigth: number, fotos: Foto[] | undefined }>()
const hoveredIds = ref<(number | undefined)[]>([])
function setHoveredList(ids: (number | undefined)[]) {
    console.log(ids)
    hoveredIds.value = ids
}

</script>

<style scoped>
.gridA {
    display: grid;
    grid-template-columns: 4fr 1fr;
    grid-template-rows: 4fr 1fr;
    gap: 0px 0px;
    grid-template-areas:
        ". ."
        ". .";
}

.gridB {
    display: grid;
    grid-template-columns: 4fr;
    grid-template-rows: 4fr 1fr;
    gap: 0px 0px;
    grid-template-areas:
        "."
        ".";
}

.gridC {
    display: grid;
    grid-template-columns: 4fr 1fr;
    grid-template-rows: 4fr;
    gap: 0px 0px;
    grid-template-areas:
        ". .";
}

.gridD {
    display: grid;
    grid-template-columns: 4fr;
    grid-template-rows: 4fr;
    gap: 0px 0px;
    grid-template-areas:
        ".";
}
</style>