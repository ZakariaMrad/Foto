<template>
    <div>
        <v-row>
            <v-col>
                <h4 v-if="archived">Liste de plaintes archivés</h4>
                <h4 v-else>Liste de plaintes</h4>
                <ComplaintList :complaints="complaints" />

                <v-btn v-if="archived" class="mt-1" color="blue" @click="toggleComplaints">
                    Afficher les plaintes actives
                </v-btn>
                <v-btn v-else class="mt-1" color="blue" @click="toggleComplaints">
                    Afficher les plaintes archivés
                </v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Complaint from '../../models/Complaint';
import ComplaintRepository from '../../repositories/ComplaintRepository';
import ComplaintList from './complaintList.vue';

const emit = defineEmits(['reloadComplaints']);
const complaints = ref<Complaint[]>([]);
const archived = ref<Boolean>(false);

onMounted(async () => {
    await getComplaints();
})

async function getComplaints() {
    let apiResponse = await ComplaintRepository.getComplaints();
    if (!apiResponse.success || !apiResponse.data) return;
    complaints.value = apiResponse.data as Complaint[];
}
async function getArchivedComplaints() {
    let apiResponse = await ComplaintRepository.getComplaints();
    if (!apiResponse.success || !apiResponse.data) return;
    complaints.value = apiResponse.data as Complaint[];
}

function toggleComplaints() {
    archived.value = !archived.value;
    if (archived) {
        getArchivedComplaints();
    } else {
        getComplaints();
    }
}

</script>

<style scoped></style>