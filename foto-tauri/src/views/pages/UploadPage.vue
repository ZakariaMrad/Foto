<template>
    <DefaultLayout>
        <div class="d-flex align-center flex-column mt-3">
            <h1 class="text-h2 text-bold">Téléversement</h1>
        </div>
        <div class="w-50 mx-auto image-input">
            <v-file-input label="Parcourir..." 
                        variant="solo" 
                        accept="image/png, image/jpeg, image/webp" 
                        multiple 
                        chips
                        prepend-icon="mdi-camera"
                        v-model="files"
                        @update:model-value="readFiles">
            </v-file-input>
        </div>

        <v-container>
            <v-row justify="center">
                <v-col cols="3" v-for="(file, index) in files">
                    <v-card v-if="pictures[index]">
                        <v-img
                            height="300"
                            :src="pictures[index]?.base64"
                            :style="{filter: 'saturate(' + pictures[index]?.saturation +'%) contrast(' + pictures[index]?.contrast +'%) brightness(' + pictures[index]?.exposition +'%)'}"
                            cover
                            >
                            <v-toolbar
                                color="rgba(0, 0, 0, 0)"
                            >
                                <template v-slot:prepend>
                                    <v-btn variant="tonal" icon="mdi-pencil" @click="openEditModal(index)"></v-btn>
                                </template>

                                <template v-slot:append>
                                    <v-btn variant="tonal" icon="$close" @click="removeFromFiles(file)"></v-btn>
                                </template>
                            </v-toolbar>
                        </v-img>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <div class="d-flex align-center justify-center mt-2 mb-5">
            <v-btn v-if="files.length !== 0" @click="uploadFotos()">
                Téléverser
            </v-btn>
        </div>
        
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '../layouts/DefaultLayout.vue';
import { ref } from 'vue';
import {EventsBus, Events} from '../../core/EventBus';
import FotoRepository from '../../repositories/FotoRepository';
import Foto from '../../models/Foto';
import EditedPicture from '../../models/EditedPicture';
const {eventBusEmit} = EventsBus();

const pictures = ref<Array<EditedPicture>>([]);
const files = ref([]);

function readFiles() {
    files.value.forEach((file, index) => {
        const reader = new FileReader();
        reader.onloadend = function() {
            pictures.value[index] = new EditedPicture((reader.result as string));
        }
        if (file) {
            reader.readAsDataURL(file);
        } else {
            delete pictures.value[index];
        }
    });
    console.log(pictures.value);
}

function removeFromFiles(file: File)
{
    const index = files.value.findIndex((f) => f === file);
    files.value = files.value.filter((f) => f !== file);
    pictures.value = pictures.value.filter((_, picIndex) => picIndex !== index)
}

function removeAllFiles()
{
    files.value = [];
    pictures.value = [];
}

function openEditModal(index: number) {
    eventBusEmit(Events.OPEN_EDIT_MODAL, pictures.value[index]);
}

function uploadFotos() {
    //TODO: Webworker
    pictures.value.forEach(async (picture, index) => {
        let foto = new Foto();
        foto.name = "test" + index;
        foto.base64image = picture.base64;

        let response = await FotoRepository.uploadFotos(foto);
        console.log(response);
        
        if (response.success)
            removeAllFiles();
    });
    
}

</script>

<style scoped>

.image-input {
  margin-top: 100px;
}

</style>