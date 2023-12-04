<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card>
            <div class="text-center">
                <h4 class="ma-5"> Êtes vous certain de ne plus vouloir suivre cet utilisateur ?</h4>
            </div>
            <v-card-text>
                
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <div class="float-right">
                        <v-btn type="submit" class="text-white" color="green-darken-3" text="Confirmer" />
                    </div>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import delay from 'delay';

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})

// onMounted(async () => {

// })

async function closeDialog() {
    await delay(1000);
    emit('closeDialog');
}
</script>

<style scoped></style>