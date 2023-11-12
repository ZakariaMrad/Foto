<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">

                <v-card-text>
                    <v-row>
                        <v-col>
                            <p class="text-center text-danger">{{ errorMessage }}</p>
                            <AssetPicker :itemSize="6" :items="selectedfotos" title="Fotos selectionées" :multiple="false"
                                unselectable />
                        </v-col>
                        <v-col>
                            <AssetPicker :itemSize="6" :items="fotos" title="Fotos" :multiple="true"
                                @items-selected="(items) => setItems(items as Foto[])" />
                        </v-col>
                    </v-row>
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
import { onMounted, ref } from 'vue';
import Album from '../../models/Album';
import Foto from '../../models/Foto';
import AssetPicker from '../AssetPicker.vue';
import FotoRepository from '../../repositories/FotoRepository';
import { useFormHandler } from 'vue-form-handler'

const props = defineProps<{ album: Partial<Album> }>()
const errorMessage = ref<string | undefined>(undefined);


const { register, handleSubmit, formState, unregister } = useFormHandler({
    validationMode: 'always',
})
const successFn = async (partialAlbum: any) => {
    loading.value = true;
    errorMessage.value = undefined;
    const album: Partial<Album> = { ...props.album, ...partialAlbum };
        
    if (!album.fotos) {
        errorMessage.value = "Vous devez choisir au moins une foto";
        loading.value = false;
        return;
    }

    emit('nextStep', album);
    loading.value = false;
}

function registerItems(items: Foto[]) {
    errorMessage.value = undefined;
    if (items.length === 0) {
        errorMessage.value = "Vous devez choisir au moins une foto";
        loading.value = false;
        return;
    }
    unregister('fotos');
    register('fotos', {
        defaultValue: items
    })

}
const submitFn = () => {
    try {
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}


const loading = ref(false);
const success = ref(false);

const fotos = ref<Foto[]>([])
const selectedfotos = ref<Foto[]>([])

const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void , (event: 'back'): void }>()

onMounted(async () => {
    let apiResponse = await FotoRepository.getFotos()
    if (!apiResponse.success) return

    fotos.value = apiResponse.data
    registerItems([]);  
    if (props.album.fotos) register('fotos', { defaultValue: props.album.fotos })
    selectedfotos.value = props.album.fotos || []  
})

function setItems(items: Foto[]) {
    selectedfotos.value = [...props.album.fotos || [], ...items]
    registerItems(selectedfotos.value)

}

function closeDialog() {
    emit('closeDialog');
}
function back() {
    emit('back');
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