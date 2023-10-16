<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">
                <v-card-text>
                    <v-row>
                        <v-col>
                            <v-text-field class="mb-1" label="Titre" required v-bind="register('title')"
                                :error-messages="titleErrorMessage" />
                            <v-switch hide-details color="blue"
                                v-bind:input-value="register('isPublic', { defaultValue: true })" label="Public" />
                            <v-textarea rows="2" v-bind="register('notes')" type="" label="Notes" />
                        </v-col>
                        <v-col>
                            <v-textarea rows="8" v-bind="register('description')" type="" label="Description" />
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions class="card-actions">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
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
import { useFormHandler } from 'vue-form-handler'
const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})
const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void }>()
const props = defineProps<{ album: Partial<Album> }>()

const loading = ref(false);
const success = ref(false);
const titleErrorMessage = ref();

const successFn = async (partialAlbum: Partial<Album>) => {
    loading.value = true;
    titleErrorMessage.value = undefined;
    const album = { ...props.album, ...partialAlbum };
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