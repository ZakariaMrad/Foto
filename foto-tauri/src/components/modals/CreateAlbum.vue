<template>
    <v-dialog width="90%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <h3 class="text-center" md="12">Créer un album</h3>
                <v-responsive :aspect-ratio="3 / 1">
                    <v-card height="99%">
                        <v-tabs v-model="tab" color="deep-purple-accent-4" fixed-tabs>
                            <v-tab value="0" prepend-icon="mdi-information-outline">Informations</v-tab>
                            <v-tab value="1" :disabled="disabledTabs.fotos"
                                prepend-icon="mdi-panorama-variant-outline">Fotos</v-tab>
                            <v-tab value="2" :disabled="disabledTabs.display"
                                prepend-icon="mdi-application-outline">Affichage</v-tab>
                            <v-tab value="3" :disabled="disabledTabs.collaboraters"
                                prepend-icon="mdi-account-group">Collaborateurs</v-tab>
                            <v-tab value="4" :disabled="disabledTabs.finalization"
                                prepend-icon="mdi-checkbox-marked-circle-outline"> Finalisation</v-tab>
                        </v-tabs>
                        <v-card-text>
                            <v-window v-model="tab">
                                <v-window-item value="0">
                                    <Information :album="albumInProgress" @next-step="(album) => infoToFotos(album)"
                                        @close-dialog="closeDialog()" />
                                </v-window-item>

                                <v-window-item value="1">
                                    <PickFotos :album="albumInProgress" @next-step="(album) => fotoToDisplay(album)"
                                        @close-dialog="closeDialog()" />
                                </v-window-item>

                                <v-window-item value="2">
                                    <Display :album="albumInProgress" @next-step="(album) => displayToCollaboraters(album)"
                                        @close-dialog="closeDialog()" />
                                </v-window-item>
                                <v-window-item value="3">
                                    <Collaboraters :album="albumInProgress"
                                        @next-step="(album) => collaboratersToFinalization(album)"
                                        @close-dialog="closeDialog()" />
                                </v-window-item>
                                <v-window-item value="4">
                                    <Finalization :album="(albumInProgress)" @next-step="(album) => finalization(album)"
                                        @close-dialog="closeDialog()" />
                                </v-window-item>
                            </v-window>
                        </v-card-text>
                    </v-card>
                </v-responsive>

            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Information from '../CreateAlbumComponents/Information.vue';
import Album from '../../models/Album';
import PickFotos from '../CreateAlbumComponents/PickFotos.vue';
import Display from '../CreateAlbumComponents/Display.vue';
import Collaboraters from '../CreateAlbumComponents/Collaboraters.vue';
import Finalization from '../CreateAlbumComponents/Finalization.vue';

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})
const tab = ref<number>(0);
const albumInProgress = ref<Partial<Album>>({});

const disabledTabs = ref<{ fotos: boolean, display: boolean, collaboraters: boolean, finalization: boolean }>({ fotos: true, display: true, collaboraters: true, finalization: true });

function infoToFotos(album: Partial<Album>) {
    tab.value = 1;
    disabledTabs.value.fotos = false;
    console.log(album);
    albumInProgress.value = album;
}

function fotoToDisplay(album: Partial<Album>) {
    tab.value = 2;
    disabledTabs.value.display = false;
    console.log(album);
    albumInProgress.value = album;
}
function displayToCollaboraters(album: Partial<Album>) {
    tab.value = 3;
    disabledTabs.value.collaboraters = false;
    console.log(album);
    albumInProgress.value = album;
}
function collaboratersToFinalization(album: Partial<Album>) {
    tab.value = 4;
    disabledTabs.value.finalization = false;
    console.log(album);
    albumInProgress.value = album;
}

function finalization(album: Partial<Album>) {
    tab.value = 0;
    disabledTabs.value = { fotos: true, display: true, collaboraters: true, finalization: true };
    console.log(album);
    albumInProgress.value = album;
    emit('closeDialog');
}
function closeDialog() {
    albumInProgress.value = {};
    disabledTabs.value = { fotos: true, display: true, collaboraters: true, finalization: true };
    tab.value = 0;
    emit('closeDialog');
}

</script>

<style scoped></style>