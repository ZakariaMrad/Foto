<template>
    <v-dialog width="90%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <h3 class="text-center" md="12">Créer un album</h3>
                <AlbumGrid :width="3" :heigth="3" />
                <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                <div class="float-right">
                    <v-btn type="submit" class="text-white" color="green-darken-3" text="Créer" />
                </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import AlbumGrid from '../AlbumGrid2/AlbumGrid.vue';
const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

function closeDialog() {
    emit('closeDialog');
}
</script>

<style scoped></style>