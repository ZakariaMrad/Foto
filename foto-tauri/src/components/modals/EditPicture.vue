<template>
    <v-dialog width="70%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <v-container>
                    <v-row>
                        <v-col cols="9">
                            <p>Edit</p>
                        </v-col>
                        <v-col cols="3">
                            <v-slider></v-slider>
                            <v-slider></v-slider>
                            <v-slider></v-slider>
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

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

function closeDialog() {
    emit('closeDialog');
}
</script>

<style scoped></style>