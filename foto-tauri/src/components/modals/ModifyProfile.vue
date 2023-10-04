<template>
    <v-dialog width="40%" v-model="props.activate">
        <v-card title="Modification de profil">
            <v-card-actions>
                <v-spacer/>
                <v-btn text="Fermer" @click="closeDialog"/>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">

const props = defineProps({
    activate: Boolean
})

const emit = defineEmits(['closeDialog'])

function closeDialog(val: boolean) {
    emit('closeDialog', val);
}
</script>

<style scoped></style>