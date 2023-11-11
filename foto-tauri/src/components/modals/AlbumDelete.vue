<template>
    <v-dialog width="40%" v-model="props.activate">
        <v-card :title="'Suppression de l\'album '+ props.album.title">
            <v-card-text>
                Êtes-vous sûr de vouloir supprimer l'album ?
                <p class="text-danger">{{errorMessage}}</p>
                <p class="text-success">{{ successMessage }}</p>
            </v-card-text>
            <v-card-actions>
                <v-btn text="Non" color="green" @click="closeDialog"/>
                <v-btn text="Oui" color="red" @click="deleteAlbum"/>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Album from '../../models/Album';
import AlbumRepository from '../../repositories/AlbumRepository';
//@ts-ignore
import delay from 'delay';

const props = defineProps<{activate:boolean, album:Album}>()
const emit = defineEmits(['closeDialog', 'deletedAlbum'])

const successMessage = ref<string | undefined>(undefined);
const errorMessage = ref<string | undefined>(undefined);

function closeDialog() {
    emit('closeDialog');
}

async function deleteAlbum() {
    let apiResponse = await AlbumRepository.deleteAlbum(props.album.idAlbum!);
    console.log('deletedAlbum', apiResponse);
    if (!apiResponse.success){
        console.log(apiResponse.errors);
        
        errorMessage.value = apiResponse.errors[0].message;
        return;
    };
    successMessage.value = apiResponse.data.message;
    
    await delay(2000);
    emit('deletedAlbum');
    closeDialog();
}
</script>

<style scoped></style>