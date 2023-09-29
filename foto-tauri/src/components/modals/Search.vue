<template>
    <v-dialog width="40%" v-model="props.activate">
        <v-card title="Rechercher">
            <v-text-field v-bind:="register('searchValue')" class="pa-5" append-icon="mdi-magnify" label="Search" single-line
                hide-details/>
            <v-card-actions>
                <v-spacer/>
                <v-btn text="Fermer" @click="closeDialog"/>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { useFormHandler } from 'vue-form-handler';

const { register} = useFormHandler({
    validationMode: 'always',
})
const props = defineProps({
    activate: Boolean
})
const emit = defineEmits(['closeDialog'])

function closeDialog(val: boolean) {
    emit('closeDialog', val);
}
</script>

<style scoped></style>