<template>
    <v-dialog width="40%" v-model="props.activate">
        <v-card :title="'Modification de l\'album '+ props.album.title">
            <v-card-text>
                Il n'est pas encore possible de modifier l'album :(

            </v-card-text>
            <v-card-actions>
                <v-btn text="Fermer" color="red" @click="closeDialog"/>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import Album from '../../models/Album';

const props = defineProps<{activate:boolean, album:Album}>()
const emit = defineEmits(['closeDialog'])

function closeDialog(val: boolean) {
    emit('closeDialog', val);
}
</script>

<style scoped></style>