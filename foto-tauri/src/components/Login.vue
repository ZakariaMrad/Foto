<template>
    <v-dialog width="500" v-model="props.activate">
        <v-card>
          <v-card-text>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
          </v-card-text>
          <v-card-actions>
            <v-btn color="primary" block @click="closeDialog()">Close Dialog</v-btn>
          </v-card-actions>
        </v-card>

    </v-dialog>
</template>

<script setup lang="ts">
const props = defineProps({
    activate: Boolean
})
const emit = defineEmits(['isActivated'])

function closeDialog() {
    emit('isActivated', false);
}

</script>

<style scoped></style>