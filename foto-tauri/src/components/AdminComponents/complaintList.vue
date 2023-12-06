<template>
    <v-table fixed-header height="50vh">
        <thead>
            <tr>
                <th style="width:25%;">Accusateur</th>
                <th style="width:25%;">Accusé</th>
                <th style="width:15%;">Type</th>
                <th style="width:15%;">Date</th>
                <th style="width: 20%;text-align: center;">Ouvrir</th>
            </tr>
        </thead>
        <tbody>
            <ComplaintListItem v-for="complaint in complaints" :complaint="complaint" @reload="reload"/>
        </tbody>
    </v-table>
</template>

<script setup lang="ts">
import Complaint from '../../models/Complaint';
import ComplaintListItem from './complaintListItem.vue';

defineProps<{ complaints: Complaint[] | undefined }>()
const emit = defineEmits<{
    (event: 'reload'): void
}>()

function reload(){
    emit('reload');
}



</script>

<style scoped></style>