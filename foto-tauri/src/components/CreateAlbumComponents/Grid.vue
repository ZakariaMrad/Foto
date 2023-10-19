<template>
    <v-row>
        <v-col cols="6">
            <v-slider label="Nombre de colonnes" v-model="nbColumns" :step="1" show-ticks min="1"
                :max="nbFotos > 6 ? 6 : nbFotos" />
            <v-row>

                <v-col v-for="foto in fotos" cols="12" md="4">
                    <VueDraggableNext>
                        <v-card class="p-1" @dragstart="onDragStart(foto)" @dragend="onDragEnd">
                            <v-img :src="foto.path" aspect-ratio="1" />
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
                                        <v-img :src="getPath(y * nbColumns + x)" aspect-ratio="1" />
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

const props = defineProps<{ fotos: Foto[] }>()
const emit = defineEmits<{ (event: 'finishedGrid', grid: AlbumGrid | undefined): void }>()

const nbColumns = ref<number>(1);
const draggedFoto = ref<Foto>();
const fotos = ref<Foto[]>([]);
const fotosInGrid = ref<(Foto | undefined)[]>([]);
const nbFotos = ref<number>(0);

onMounted(() => {
    fotos.value = props.fotos;
    nbFotos.value = fotos.value.length;
})

function onDragStart(foto: Foto) {
    console.log('dragstart', foto);
    draggedFoto.value = foto;
}

function onDragEnd() {
    console.log('dragend');
    draggedFoto.value = undefined;
    if (fotos.value.length === 0) {
        let grid = new AlbumGrid();
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
    if (!foto) return 'https://placehold.co/200';
    return foto.path;
}
function removeFoto(id: number) {
    if (!fotosInGrid.value[id]) return;
    fotos.value.push(fotosInGrid.value[id]!);
    fotosInGrid.value[id] = undefined;
}

</script>

<style scoped></style>