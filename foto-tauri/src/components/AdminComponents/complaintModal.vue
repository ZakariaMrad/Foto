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
                        <PostComponent v-if="complaint?.subject.post" :post="complaint?.subject.post" />
                        <p class="text-danger">{{ errorMessage }}</p>
                        <p class="text-success">{{ successMessage }}</p>
                        <v-btn class="ma-2" color="red-darken-3" @click="deleteSubject" :loading="loading">Supprimer</v-btn>
                        <v-btn class="ma-2" color="green-darken-3" v-if="props.complaint?.status == 'Active'" @click="deleteSubjectAndArchive" :loading="loading">Supprimer puis archivé</v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-actions>
                <v-btn class="mb-5 ms-5" color="red-darken-3" @click="closeDialog()">Quitter</v-btn>
                <v-btn v-if="props.complaint?.status == 'Active'" class="mb-5 ms-5" color="green-darken-3"
                    @click="archiveComplaint()">Archivé
                    la plainte puis quiter</v-btn>
                <v-btn v-else class="mb-5 ms-5" color="green-darken-3" @click="activateComplaint()">Activé la plainte puis
                    quitter</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Complaint from '../../models/Complaint';
import complaintUser from './complaintUser.vue';
import PostComponent from '../PostComponent.vue';
import ComplaintRepository from '../../repositories/ComplaintRepository';


const status = ref<string>('');
const errorMessage = ref<string | undefined>(undefined);
const successMessage = ref<string | undefined>(undefined);
const loading = ref<boolean>(false);

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
async function archiveComplaint() {
    let apiResult = await ComplaintRepository.archiveComplaint(props.complaint!.idComplaint);
    if (!apiResult.success) {
        errorMessage.value = apiResult.errors![0].message;
        return
    }
    closeDialog();
}
async function activateComplaint() {
    let apiResult = await ComplaintRepository.activateComplaint(props.complaint!.idComplaint);
    if (!apiResult.success) {
        errorMessage.value = apiResult.errors![0].message;
        return
    }
    closeDialog();
}
async function deleteSubjectAndArchive() {
    loading.value = true;
    await deleteSubject();
    await archiveComplaint();
    loading.value = false;

}

async function deleteSubject() {
    loading.value = true;
    let apiResult = await ComplaintRepository.deleteComplaintSubject(props.complaint!.idComplaint);
    if (!apiResult.success) {
        errorMessage.value = apiResult.errors![0].message;
    } else {
        successMessage.value = apiResult.data;
    }
    loading.value = false;

}

</script>

<style scoped></style>