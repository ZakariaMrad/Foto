<template>
    <v-card>
        <v-card-title>
            <h5>{{ props.album.title }}</h5>
        </v-card-title>
        <v-list style="display: flex; flex-wrap: wrap; flex-direction:row;">
            <v-list-item subtitle="Owner" :title="props.album.owner!.name" />
            <v-list-item v-for="collaboraters in props.album.collaborators" subtitle="Collaborater"
                :title="collaboraters.name" />
        </v-list>
        <div class="d-flex justify-space-between">
            <v-btn color="green-darken-3" class=" mx-5" @click="toggleModifyAlbum()"> Modifier l'album</v-btn>
            <v-btn color="red-darken-3" class=" mx-5" @click="toggleDeleteAlbum()"> Supprimer l'album</v-btn>
        </div>
        <v-container fluid>
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-carousel hide-delimiters height="100%" v-if="props.album.type === 'carousel'">
                            <v-carousel-item v-for="(foto, i) in props.album.fotos" :key="i - 1">
                                <v-img :src="foto.path" aspect-ratio="3"></v-img>
                            </v-carousel-item>
                        </v-carousel>
                        <v-table v-else>
                            <tbody>
                                <tr v-for="(_, y) in album.grid?.nbRows">
                                    <td v-for="(_, x) in album.grid?.nbCols" class="pa-1">
                                        <v-responsive aspect-ratio="1"
                                            v-if="(y * album.grid!.nbCols + x) < album.grid?.fotosPosition.length!">
                                            <v-card height="100%">
                                                <v-img :src="getPath(y * album.grid!.nbCols + x)" aspect-ratio="1" />
                                            </v-card>
                                        </v-responsive>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
    <AlbumModification :album="props.album" :activate="activateModification" @close-dialog="toggleModifyAlbum"  :key="v1()"/>
    <AlbumDelete :album="props.album" :activate="activateDelete" @close-dialog="toggleDeleteAlbum" :key="v1()"
        @deleted-album="deletedAlbum" />
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Album from '../models/Album';
import AlbumModification from './modals/AlbumModification.vue';
import AlbumDelete from './modals/AlbumDelete.vue';
import {v1} from 'uuid';

const emit = defineEmits(['deletedAlbum'])

const props = defineProps<{
    album: Album
}>()
const activateModification = ref<boolean>(false);
const activateDelete = ref<boolean>(false);


onMounted(() => {
    console.log('album', props.album.collaborators);

})

const getPath = (index: number) => {
    const id = props.album.grid!.fotosPosition[index];
    return props.album.fotos!.find(foto => foto.idFoto === id)?.path;
}

function toggleModifyAlbum() {
    activateModification.value = !activateModification.value;
    emit('deletedAlbum');
}
function toggleDeleteAlbum() {
    activateDelete.value = !activateDelete.value;
}
async function deletedAlbum() {

    emit('deletedAlbum');
}
</script>

<style scoped>
.horizontal-list-item {
    display: inline-block;
    flex-wrap: wrap;
}
</style>