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
                        @update:model-value="printFiles">
            </v-file-input>
        </div>

        <v-container>
            <v-row justify="center">
                <v-col v-for="(file, index) in files">
                    <v-card>
                        <v-img
                            height="200"
                            :src="imgSrc[index]"
                            cover
                            >
                            <v-toolbar
                                color="rgba(0, 0, 0, 0)"
                            >
                                <template v-slot:prepend>
                                    <v-btn variant="tonal" icon="mdi-pencil"></v-btn>
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
        <div class="d-flex align-center justify-center mt-5">
            <v-btn v-if="files.length !== 0">
                Téléverser
            </v-btn>
        </div>
        
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '../layouts/DefaultLayout.vue';
import { ref } from 'vue';


const imgSrc = ref<Array<string | null | ArrayBuffer>>([]);
const files = ref([]);

function printFiles() {
    console.log(files.value);

    files.value.forEach((file, index) => {
        const reader = new FileReader();
        reader.onloadend = function() {
            imgSrc.value[index] = reader.result;
        }
        if (file) {
            reader.readAsDataURL(file);
        } else {
            imgSrc.value[index] = "";
        }
    })
}

function removeFromFiles(file: File)
{
    const index = files.value.findIndex((f) => f === file);
    files.value = files.value.filter((f) => f !== file);
    imgSrc.value = imgSrc.value.filter((_, imgIndex) => imgIndex !== index)
}

</script>

<style scoped>

.image-input {
  margin-top: 100px;
}

</style>