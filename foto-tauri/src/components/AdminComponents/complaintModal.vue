<template>
    <v-dialog width="90%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <v-card-title class="text-center">Plainte #{{ complaint?.idComplaint }}</v-card-title>
                <v-row>
                    <v-col cols="6">
                        <v-row>
                            <complaintUser :account="complaint?.recipient" role="Accusé" />
                        </v-row>
                        <v-row>
                            <complaintUser :account="complaint?.sender" role="Accusateur" />
                        </v-row>
                    </v-col>
                    <v-col cols="6">

                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn class="mb-5 ms-5" color="red-darken-3" @click="closeDialog()">Quitter</v-btn>
                <v-btn v-if="status == 'Active'" class="mb-5 ms-5" color="green-darken-3" @click="archiveComplaint()">Archivé
                    la plainte puis fermer</v-btn>
                <v-btn v-else class="mb-5 ms-5" color="green-darken-3" @click="activateComplaint()">Activé la plainte puis
                    fermer</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Complaint from '../../models/Complaint';
import complaintUser from './complaintUser.vue';


const status = ref<string>('');

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean,
    complaint: Complaint
})

onMounted(() => {
    getStatus();
})

function closeDialog() {
    emit('closeDialog');
}
function getStatus() {
    status.value = props.complaint?.status == "archived" ? "Archivé" : "Active";
}
function archiveComplaint() {
    console.log('archive complaint');
    closeDialog();
}
function activateComplaint() {
    console.log('activate complaint');
    closeDialog();
}

</script>

<style scoped></style>