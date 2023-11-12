<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-text-field class="mb-1" label="Titre" required v-bind="register('title')"
                                :error-messages="titleErrorMessage" v-model="props.album.title" />
                            <v-switch hide-details color="blue"
                                v-model="isPublic" label="Public" />
                            <v-textarea rows="2" v-bind="register('notes')" v-model="props.album.notes" type="" label="Notes" />
                        </v-col>
                        <v-col>
                            <v-textarea rows="8" v-bind="register('description')" type="" label="Description" v-model="props.album.description" />
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions class="card-actions">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn class="btn" color="indigo" disabled>Précédent</v-btn>
                    <v-btn type="submit" :success="success" :loading="loading" class="text-white" color="green-darken-3"
                        text="Suivant" />
                </v-card-actions>
            </form>
        </v-card>
    </v-responsive>
</template>
  
<script setup lang="ts">
//TODO: il faut être capable de faire un reset du formulaire lors d'un back()
import { onMounted, ref } from 'vue';
import Album from '../../models/Album';
import { useFormHandler } from 'vue-form-handler'
const { register, handleSubmit, formState, unregister } = useFormHandler({
    validationMode: 'always',
})
const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void }>()
const props = defineProps<{ album: Partial<Album> }>()

const loading = ref(false);
const success = ref(false);
const titleErrorMessage = ref();
const isPublic = ref(false);

onMounted(() => {
  if(props.album.title) register('title', { defaultValue: props.album.title })
    if(props.album.notes) register('notes', { defaultValue: props.album.notes })
    if(props.album.description) register('description', { defaultValue: props.album.description })
    if(props.album.isPublic) register('isPublic', { defaultValue: props.album.isPublic })  
})


const successFn = async (partialAlbum: Partial<Album>) => {
    loading.value = true;
    titleErrorMessage.value = undefined;
    const album = { ...partialAlbum,...props.album };
    
    if (!album.title) {
        loading.value = false;
        return;
    };
    if (album.title.length < 3) {
        loading.value = false;
        titleErrorMessage.value = "Le titre doit contenir au moins 3 caractères";
        return;
    }

    emit('nextStep', album);
    loading.value = false;
    success.value = true;

}

const submitFn = () => {
    try {
        unregister('isPublic');
        register('isPublic', { defaultValue: isPublic.value })        
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}
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