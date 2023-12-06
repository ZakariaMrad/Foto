<template>
    <v-row>
        <v-col cols="6">
            <v-slider label="Nombre de colonnes" v-model="nbColumns" :step="1" show-ticks min="1"
                :max="nbFotos > 6 ? 6 : nbFotos" @end="onDragEnd" />
            <v-row>

                <v-col v-for="foto in fotos" cols="12" md="4">
                    <VueDraggableNext>
                        <v-card class="p-1" @dragstart="onDragStart(foto)" @dragend="onDragEnd">
                            <v-img :src="foto.path" aspect-ratio="1"
                                :style="{ filter: 'saturate(' + foto.saturation + '%) contrast(' + foto.contrast + '%) brightness(' + foto.exposition + '%)' }" />
                        </v-card>
                    </VueDraggableNext>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="6">
            <v-table>
                <tbody>
                    <tr v-for="(_, y) in Math.ceil(nbFotos / nbColumns)">
                        <td v-for="(_, x) in nbColumns" class="pa-1">
                            <v-responsive aspect-ratio="1">
                                <VueDraggableNext @drop="onDrop(y * nbColumns + x)" style="height: 100%;">
                                    <v-card v-if="(y * nbColumns + x) < nbFotos" height="100%"
                                        @click="removeFoto(y * nbColumns + x)">
                                        <v-img :src="getPath(y * nbColumns + x)" aspect-ratio="1"
                                            :style="{ filter: 'saturate(' + getFoto(y * nbColumns + x)?.saturation + '%) contrast(' + getFoto(y * nbColumns + x)?.contrast + '%) brightness(' + getFoto(y * nbColumns + x)?.exposition + '%)' }" />
                                    </v-card>
                                </VueDraggableNext>
                            </v-responsive>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </v-col>
    </v-row>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { VueDraggableNext } from 'vue-draggable-next';
import Foto from '../../models/Foto';
import AlbumGrid from '../../models/AlbumGrid';
import Album from '../../models/Album';

const props = defineProps<{ fotos: Foto[], album: Album }>()
const emit = defineEmits<{ (event: 'finishedGrid', grid: AlbumGrid | undefined): void }>()

const nbColumns = ref<number>(1);
const draggedFoto = ref<Foto>();
const fotos = ref<Foto[]>([]);
const fotosInGrid = ref<(Foto | undefined)[]>([]);
const nbFotos = ref<number>(0);

onMounted(() => {
    setup()
    onDragEnd();

})
function setup() {
    fotos.value = props.fotos;
    nbFotos.value = fotos.value.length;
    nbColumns.value = nbFotos.value > 6 ? 6 : nbFotos.value;
    console.log('setup', props.album.grid);
    
    if (!props.album.grid) {
        fotos.value.forEach((foto, index) => {
            fotosInGrid.value[index] = foto;
        })
        fotos.value = fotos.value.filter(f => !fotosInGrid.value.includes(f));
    } else {
        nbColumns.value = props.album.grid.nbCols;
        fotosInGrid.value = props.album.grid.fotosPosition.map(id => fotos.value.find(f => f.idFoto === id));
        fotos.value = fotos.value.filter(f => !fotosInGrid.value.includes(f));

    }
    onDragEnd();
    //we need to put every foto in a grid cell

}


function onDragStart(foto: Foto) {
    console.log('dragstart', foto);
    draggedFoto.value = foto;
}

function onDragEnd() {
    console.log('dragend');
    draggedFoto.value = undefined;
    if (fotos.value.length === 0) {
        let grid = props.album.grid || new AlbumGrid();
        console.log('gid', grid);

        grid.nbCols = nbColumns.value;
        console.log(nbColumns.value);

        grid.nbRows = Math.ceil(nbFotos.value / nbColumns.value);
        grid.fotosPosition = fotosInGrid.value.map(f => f!.idFoto);

        emit('finishedGrid', grid);
    } else {
        emit('finishedGrid', undefined);
    }
}

function onDrop(id: number) {
    if (!draggedFoto.value) return;
    if (fotosInGrid.value[id] !== undefined) {
        removeFoto(id);
    };
    fotosInGrid.value[id] = draggedFoto.value;
    fotos.value = fotos.value.filter((f) => f.idFoto !== draggedFoto.value?.idFoto);
}
function getPath(id: number) {
    const foto = fotosInGrid.value[id];
    if (!foto) return 'https://placehold.co/4000';
    return foto.path;
}
function getFoto(id: number) {
    const foto = fotosInGrid.value[id];
    if (!foto) return;
    return foto;
}
function removeFoto(id: number) {
    if (!fotosInGrid.value[id]) return;
    fotos.value.push(fotosInGrid.value[id]!);
    fotosInGrid.value[id] = undefined;
}

</script>

<style scoped></style>