<template>
    <tr>
        <th>{{ complaint?.sender.name }} - {{ complaint?.sender.email }}</th>
        <th>{{ complaint?.recipient.name }} - {{ complaint?.recipient.email }}</th>
        <th>{{ getType(complaint?.subject) }}</th>
        <th>{{ formatDate(complaint?.sentDateTime.date) }}</th>
        <th style="text-align: center;">
            <v-btn color="green-darken-3" @click="openModalComplaint" >Ouvrir</v-btn>
        </th>
        <ComplaitModal :complaint="complaint" :activate="activate" @closeDialog="activate=false"/>
    </tr>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Complaint from '../../models/Complaint.ts';
import ComplaintSubject from '../../models/ComplaintSubject';
import ComplaitModal from './complaintModal.vue';

defineProps({
    complaint: Complaint
})

const activate = ref(false);

function openModalComplaint() {
    activate.value = true;
}

function getType(subject: ComplaintSubject | undefined) {
    if (!subject) return '';
    if (subject.album !== undefined) return 'Album';
    if (subject.post !== undefined) return 'Post';
    if (subject.foto !== undefined) return 'Foto';
    return '';
}

function formatDate(date: string | undefined) {
    if (!date) return '';
    const formattedDate = new Date(date).toLocaleDateString("fr-CA") + " à " + new Date(date).toLocaleTimeString("fr-CA") ;
    // const formattedDate = new Date(date).toLocaleDateString("fr-CA");
    return formattedDate;
}
</script>
<style scoped></style>
