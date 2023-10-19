<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">

                <v-card-text>
                    <v-tabs v-model="tab" color="deep-purple-accent-4" fixed-tabs>
                        <v-tab value="0" prepend-icon="mdi-view-carousel-outline">Carrousel</v-tab>
                        <v-tab value="1" prepend-icon="mdi-grid">Grille</v-tab>
                    </v-tabs>
                    <v-window v-model="tab" class="mt-1">
                        <v-window-item value="0">
                            <v-carousel hide-delimiters height="100%" v-if="album.fotos?.length !== 0">
                                <v-carousel-item v-for="(foto, i) in album.fotos" :key="i - 1">
                                    <v-img :src="foto.path" aspect-ratio="3"></v-img>
                                </v-carousel-item>
                            </v-carousel>
                        </v-window-item>
                        <v-window-item value="1">
                            <p class="text-center text-danger">{{ errorMessage }}</p>
                            <Grid :fotos="album.fotos!" @finishedGrid="finishedGrid" />
                        </v-window-item>
                    </v-window>
                </v-card-text>
                <v-card-actions align-end class="card-actions">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn class="btn" color="indigo" @click="back()">Précédent</v-btn>
                    <v-btn type="submit" :success="success" :loading="loading" class="text-white" color="green-darken-3"
                        text="Suivant" />
                </v-card-actions>
            </form>
        </v-card>
    </v-responsive>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Album from '../../models/Album';
import Grid from './Grid.vue';
import { useFormHandler } from 'vue-form-handler'
import AlbumGrid from '../../models/AlbumGrid';
const props = defineProps<{ album: Partial<Album> }>()
const tab = ref<number>(0);
const tabToType = ['carousel', 'grid']
const albumGrid = ref<AlbumGrid | undefined>(undefined);
const errorMessage = ref<string | undefined>(undefined);

const { register, handleSubmit, formState, unregister } = useFormHandler({
    validationMode: 'always',
})
const successFn = async (partialAlbum: any) => {
    loading.value = true;
    const album: Partial<Album> = { ...props.album, ...partialAlbum };
    emit('nextStep', album);
    loading.value = false;
}

function registerType() {
    unregister('type');
    register('type', {
        defaultValue: tabToType[tab.value],
    })
}
function registerGrid() {
    unregister('grid');
    register('grid', {
        defaultValue: albumGrid.value,
    })
}
const submitFn = () => {
    try {
        errorMessage.value = undefined;
        registerType()
        if (tab.value == 1) {
            registerGrid();
            if (!albumGrid.value) {
                errorMessage.value = "Vous devez finir la grille!";
                return;
            }
        } else {
            unregister('grid');
        }
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}

const loading = ref(false);
const success = ref(false);


const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void, (event: 'back'): void }>()

function closeDialog() {
    emit('closeDialog');
}
function back() {
    emit('back');
}

function finishedGrid(grid: AlbumGrid | undefined) {
    albumGrid.value = grid;
}

</script>

<style scoped>
.card-outter {
    padding-bottom: 50px;
}

.card-actions {
    position: absolute;
    bottom: 0;
    right: 0;
}
</style>