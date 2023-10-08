<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">

                <v-card-text>
                    <v-tabs v-model="tab" color="deep-purple-accent-4" fixed-tabs>
                        <v-tab value="0" prepend-icon="mdi-view-carousel-outline">Carrousel</v-tab>
                        <v-tab value="1" disabled prepend-icon="mdi-grid">Grille</v-tab>
                    </v-tabs>
                    <v-window v-model="tab" class="mt-1">
                        <v-window-item value="0">
                            <v-carousel hide-delimiters height="100%" v-if="album.fotos?.length!==0">
                                <v-carousel-item v-for="(foto, i) in album.fotos" :key="i-1">
                                    <v-img :src="foto.path" aspect-ratio="3"></v-img>
                                </v-carousel-item>
                            </v-carousel>
                        </v-window-item>
                        <v-window-item value="1">
                            <p>Grille</p>
                        </v-window-item>
                    </v-window>
                </v-card-text>
                <v-card-actions align-end class="card-actions">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn type="submit" :success="success" :loading="loading" class="text-white" color="green-darken-3"
                        text="Suivant" />
                </v-card-actions>
            </form>
        </v-card>
    </v-responsive>
</template>

<script setup lang="ts">
import {  onMounted, ref } from 'vue';
import Album from '../../models/Album';
import { useFormHandler } from 'vue-form-handler'
const props = defineProps<{ album: Partial<Album> }>()
const tab = ref<number>(0);
const tabToType=['carrousel','grid']

const { register, handleSubmit, formState, unregister } = useFormHandler({
    validationMode: 'always',
})
const successFn = async (partialAlbum: any) => {
    loading.value = true;
    const album: Partial<Album> = { ...props.album, ...partialAlbum };
    console.log(album);

    emit('nextStep', album);
    loading.value = false;
}

function registerType() {
    unregister('type');
    register('type', {
        defaultValue: tabToType[tab.value],
    })

}
const submitFn = () => {
    try {
        registerType()
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}

onMounted(() => {
    console.log(props.album.fotos);
      
})

const loading = ref(false);
const success = ref(false);


const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void }>()

function closeDialog() {
    emit('closeDialog');
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