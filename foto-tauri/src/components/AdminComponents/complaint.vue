<template>
    <v-card variant="outlined" class="pa-2">

        <v-row class="ma-1">
            <v-col cols="4">
                <v-row class="p-1">
                    Envoyé par <span class="ms-1 text-green">{{complaint?.sender.name}}</span>
                </v-row>
                <v-row class="p-1">
                    Envers <span class="ms-1 text-red-darken-3">{{complaint?.recipient.name}}</span>
                </v-row>
            </v-col>
            <v-col cols="8">
                <v-row>
                    <span class="p-1">Type: </span><span class="ms-1 p-1 text-red-darken-3 border rounded">Zakaria</span>
                </v-row>
                <v-row>
                    <v-btn-toggle v-model="actionbarModel">
                        <v-btn>
                            <v-icon color="" size="large" icon="mdi-magnify"/>
                         </v-btn>
                        <v-btn>
                            <v-icon color="blue" size="large" icon="mdi-read"/>
                         </v-btn>
                        <v-btn>
                            <v-icon color="red-darken-3" size="large" icon="mdi-delete" @click="deleteComplaint"/>
                         </v-btn>
                        <v-btn>
                            <v-icon color="green" size="large" icon="mdi-check-bold"/>
                         </v-btn>
                    </v-btn-toggle>
                </v-row>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Complaint from '../../models/Complaint.ts';
import ComplaintRepository from '../../repositories/ComplaintRepository';

const props = defineProps({
    complaint: Complaint
})
const actionbarModel = ref<number>();

function deleteComplaint(){
    console.log('delete complaint');
    ComplaintRepository.deleteComplaint(props.complaint?.idComplaint as number);
}
</script>

<style scoped></style>