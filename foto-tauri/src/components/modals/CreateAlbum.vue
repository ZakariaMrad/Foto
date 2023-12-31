<template>
    <v-dialog width="90%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <h3 class="text-center" md="12">Créer un album</h3>
                <p class="text-danger">{{ errorMessage }}</p>
                <p class="text-success">{{ successMessage }}</p>
                <v-responsive :aspect-ratio="3 / 1">
                    <v-card height="99%">
                        <v-tabs v-model="tab"  color="deep-purple-accent-4" fixed-tabs>
                            <v-tab value="0" :disabled="disabledTabs.infos" prepend-icon="mdi-information-outline">Informations</v-tab>
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
                                    <Information :key="v1()" :album="albumInProgress" @next-step="(album) => infoToFotos(album)"
                                        @close-dialog="closeDialog()" />
                                </v-window-item>

                                <v-window-item value="1">
                                    <PickFotos :key="v1()" :album="albumInProgress"
                                        @next-step="(album) => fotoToDisplay(album)" @close-dialog="closeDialog()"
                                        @back="fotosToInfo()" />
                                </v-window-item>

                                <v-window-item value="2">
                                    <Display :key="v1()" :album="albumInProgress"
                                        @next-step="(album) => displayToCollaboraters(album)" @close-dialog="closeDialog()"
                                        @back="displayToFotos()" />
                                </v-window-item>
                                <v-window-item value="3" :disabled="collabDisabled">
                                    <Collaboraters :key="v1()" :album="albumInProgress" :disabled="false"
                                        @next-step="(album) => collaboratersToFinalization(album)"
                                        @close-dialog="closeDialog()" @back="collaboratersToDisplay()" />
                                </v-window-item>
                                <v-window-item value="4">
                                    <Finalization :key="v1()" :album="(albumInProgress)"
                                        @next-step="(album) => finalization(album)" @close-dialog="closeDialog()"
                                        @back="finalizationToCollaboraters()" />
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
import {  ref } from 'vue';
import Information from '../CreateAlbumComponents/Information.vue';
import Album from '../../models/Album';
import PickFotos from '../CreateAlbumComponents/PickFotos.vue';
import Display from '../CreateAlbumComponents/Display.vue';
import Collaboraters from '../CreateAlbumComponents/Collaboraters.vue';
import Finalization from '../CreateAlbumComponents/Finalization.vue';
import { EventsBus, Events } from '../../core/EventBus';
import {v1} from 'uuid';

//@ts-ignore
import delay from 'delay';
import AlbumRepository from '../../repositories/AlbumRepository';

const { eventBusEmit } = EventsBus();

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})
const tab = ref<number>(0);
const albumInProgress = ref<Partial<Album>>({});
const fotosKey = ref<number>(0);
const displayKey = ref<number>(0);
const collaboratersKey = ref<number>(0);
const finalizationKey = ref<number>(0);
const collabDisabled = ref<boolean>(false);
    const errorMessage = ref<string>('');
const successMessage = ref<string>('');

const disabledTabs = ref<{ infos: boolean, fotos: boolean, display: boolean, collaboraters: boolean, finalization: boolean }>({ infos:false, fotos: true, display: true, collaboraters: true, finalization: true });


function infoToFotos(album: Partial<Album>) {
    tab.value = 1;
    disabledTabs.value.fotos = false;
    disabledTabs.value.infos = true;
    console.log(album);
    albumInProgress.value = album;
    console.log(disabledTabs.value);
    
}
function fotosToInfo() {
    disabledTabs.value.infos = false;
    disabledTabs.value.fotos = true;
    console.log(disabledTabs.value);
    fotosKey.value++;
    tab.value = 0;
}

function fotoToDisplay(album: Partial<Album>) {
    disabledTabs.value.display = false;
    disabledTabs.value.fotos = true;
    console.log(album);
    albumInProgress.value = album;
    tab.value = 2;
}
function displayToFotos() {
    disabledTabs.value.display = true;
    disabledTabs.value.fotos = false;
    fotosKey.value++;
    displayKey.value++;
    tab.value = 1;
}
function displayToCollaboraters(album: Partial<Album>) {
    disabledTabs.value.collaboraters = false;
    disabledTabs.value.display = true;
    console.log(album);
    albumInProgress.value = album;
    tab.value = 3;
}
function collaboratersToDisplay() {
    disabledTabs.value.collaboraters = true;
    disabledTabs.value.display = false;
    displayKey.value++;
    collaboratersKey.value++;
    tab.value = 2;
}
function collaboratersToFinalization(album: Partial<Album>) {
    disabledTabs.value.finalization = false;
    disabledTabs.value.collaboraters = true;
    console.log(album);
    albumInProgress.value = album;
    tab.value = 4;
}
function finalizationToCollaboraters() {
    disabledTabs.value.finalization = true;
    disabledTabs.value.collaboraters = false;
    collaboratersKey.value++;
    finalizationKey.value++;
    tab.value = 3;
}

async function finalization(album: Partial<Album>) {
    albumInProgress.value = album;
    let apiResult = await AlbumRepository.createAlbum(album as Album);
    if (!apiResult.success) {
        errorMessage.value = apiResult.errors![0].message;
        return
    }
    //@ts-ignore
    successMessage.value = apiResult.data!.message;
    await delay(2000);
    tab.value = 0;
    disabledTabs.value = { infos:true,fotos: true, display: true, collaboraters: true, finalization: true };
    eventBusEmit(Events.RELOAD_ALBUMS)
    emit('closeDialog');
}
function closeDialog() {
    albumInProgress.value = {};
    disabledTabs.value = { infos:true,fotos: true, display: true, collaboraters: true, finalization: true };
    tab.value = 0;

    emit('closeDialog');
}

</script>

<style scoped></style>