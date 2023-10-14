<template>
    <v-dialog width="70%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="9">
                            <v-img
                            class="foo"
                            max-height="600"
                            src="/Capture d’écran, le 2023-09-21 à 17.29.06.png"
                            :style="{filter: 'saturate(' + saturation +'%) contrast(' + contrast +'%) brightness(' + exposure +'%)'}">
                        
                        </v-img>
                        </v-col>
                        <v-col cols="3">
                            <div class="text-caption">Exposition</div>
                            <v-slider :max="max" :min="min" v-model="exposure"></v-slider>
                            <div class="text-caption">Contraste</div>
                            <v-slider :max="max" :min="min" v-model="contrast"></v-slider>
                            <div class="text-caption">Saturation</div>
                            <v-slider :max="max" :min="min" v-model="saturation"></v-slider>
                        </v-col>
                    </v-row>
                </v-container>
                <div class="d-flex justify-space-between">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn class="btn btn-success" @click="closeDialog()" color="green-darken-3">Sauvegarder</v-btn>
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const max = ref(200);
const min = ref(0);
const defaultValue = 100;

const exposure = ref(defaultValue);
const contrast = ref(defaultValue);
const saturation = ref(defaultValue);

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

function closeDialog() {
    emit('closeDialog');
}
</script>

<style scoped>
.foo {
    filter: saturate(v-bind(saturation));
}
</style>