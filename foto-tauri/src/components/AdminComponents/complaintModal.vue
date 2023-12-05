<template>
    <v-dialog width="90%" v-model="props.activate">
        <v-card>
            <v-card-text>
            </v-card-text>
            <v-card-actions>
                <v-btn class="mb-5 ms-5" color="red-darken-3" @click="closeDialog()">Quitter</v-btn>
            </v-card-actions>
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