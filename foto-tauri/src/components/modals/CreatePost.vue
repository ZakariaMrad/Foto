<template>
    <v-dialog width="40%" v-model="props.activate">
        <v-card>
            <form class="form-group">

                <h3 class="text-center">Create a post</h3>

                <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Close Dialog</v-btn>
                <div class="float-right">
                    <v-btn type="submit" class="text-white" color="green-darken-3" text="Submit" :loading="loading" />
                </div>
            </form>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

const loading = ref<boolean>(false)

function closeDialog() {
    emit('closeDialog');
}
</script>

<style scoped></style>