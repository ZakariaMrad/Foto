<template>
    <v-dialog width="70%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="9">
                            <!--<v-img
                            max-height="600"
                            :src="editedPicture?.base64"
                            :style="{filter: 'saturate(' + saturation +'%) contrast(' + contrast +'%) brightness(' + exposition +'%)'}">
                        
                        </v-img>-->
                        <VuePictureCropper
                            :img="editedPicture?.base64"
                            :style="{filter: 'saturate(' + saturation +'%) contrast(' + contrast +'%) brightness(' + exposition +'%)', maxHeigh: '600px'}"
                            :options="{
                                viewMode: 1,
                                dragMode: 'crop',
                            }"
                            :box-style="{
                                height: '100%',
                                width: '100%'
                            }"
                            @ready="cropper_ready()"
                            />

                        </v-col>
                        <v-col cols="3">
                            <div class="text-caption">Exposition</div>
                            <v-slider :max="max" :min="min" v-model="exposition"></v-slider>
                            <div class="text-caption">Contraste</div>
                            <v-slider :max="max" :min="min" v-model="contrast"></v-slider>
                            <div class="text-caption">Saturation</div>
                            <v-slider :max="max" :min="min" v-model="saturation"></v-slider>

                            <v-text-field v-model="name" type="text" label="Titre" required />
                            <v-textarea rows="4" v-model="description" type="text" label="Description"/>
                        </v-col>
                    </v-row>
                </v-container>
                <div class="d-flex justify-space-between">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn class="btn" @click="returnToOriginal()">Retour à l'original</v-btn>
                    <v-btn class="btn btn-success" @click="save()" color="green-darken-3">Sauvegarder modifications</v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import EditedPicture from '../../models/EditedPicture';
import { watch } from 'vue';
import VuePictureCropper, { cropper } from 'vue-picture-cropper';

const max = ref(200);
const min = ref(0);
const defaultValue = 100;

const exposition = ref(defaultValue);
const contrast = ref(defaultValue);
const saturation = ref(defaultValue);
const name = ref();
const description = ref();

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean,
    editedPicture: EditedPicture
})

watch(() => (props.activate), (value: Boolean | undefined) => {
    if (!value || !props.editedPicture)
        return;
    exposition.value = props.editedPicture.exposition;
    contrast.value = props.editedPicture.contrast;
    saturation.value = props.editedPicture.saturation;
    name.value = props.editedPicture.name;
    description.value = props.editedPicture.description;
});

function closeDialog() {
    resetSliders();
    emit('closeDialog');
}

function resetSliders() {
    if (!props.editedPicture)
        return;
    exposition.value = defaultValue;
    contrast.value = defaultValue;
    saturation.value = defaultValue;
    if(!cropper) return;
    cropper.clear();
}

function returnToOriginal() {
    if (!props.editedPicture)
        return;
    resetSliders();
    props.editedPicture.base64 = props.editedPicture.originalBase64;
}

function save() {
    if (!props.editedPicture)
        return;
    props.editedPicture.exposition = exposition.value;
    props.editedPicture.contrast = contrast.value;
    props.editedPicture.saturation = saturation.value;
    props.editedPicture.name = name.value;
    props.editedPicture.description = description.value;
    if(!cropper) return;
    props.editedPicture.base64 = cropper.getDataURL();
    
    closeDialog();
}

function cropper_ready() {
    if(!cropper) return;
    cropper.clear();
}

</script>

<style scoped>
.vue--picture-cropper__wrap {
    max-height: 600px;
    width: auto;
}
</style>