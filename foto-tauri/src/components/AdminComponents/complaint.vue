<template>
    <v-card variant="outlined" class="pa-2">

        <v-row class="ma-1">
            <v-col cols="4">
                <v-row class="p-1">
                    Envoyé par <span class="ms-1 text-green">{{ complaint?.sender.name }}</span>
                </v-row>
                <v-row class="p-1">
                    Envers <span class="ms-1 text-red-darken-3">{{ complaint?.recipient.name }}</span>

                </v-row>
            </v-col>
            <v-col cols="8">
                <v-row>
                    <span class="p-1">Type: </span><span class="ms-1 p-1 text-red-darken-3 border rounded">{{
                        getType(complaint?.subject) }}</span>
                    <span class="p-1">Status: </span><span class="ms-1 p-1 text-red-darken-3 border rounded">{{
                        complaint?.status }}</span>
                </v-row>
                <v-row>
                    <v-btn-toggle v-model="actionbarModel">
                        <v-btn>
                            <v-icon color="blue" size="large" icon="mdi-read" @click="readComplaint" />
                        </v-btn>
                        <v-btn>
                            <v-icon color="green" size="large" icon="mdi-check-bold" @click="processComplaint" />
                        </v-btn>
                        <v-btn>
                            <v-icon color="red-darken-3" size="large" icon="mdi-delete" @click="deleteComplaint" />
                        </v-btn>
                        <v-btn color="red-darken-3" size="large" icon="mdi-cancel" @click="showBanUser=!showBanUser" />
                    </v-btn-toggle>

                </v-row>
            </v-col>
        </v-row>
        <v-row v-if="showBanUser">
            <v-col cols="8">
                <v-text-field label="Raison" v-model="test"/>
            </v-col>
            <v-col cols="4">
                <v-btn text="Ok" size="large" color="green-darken-3" @click="banUser"/>
                <p class="text-success">{{ banUserSuccessMessage }}</p>
                <p class="text-danger">{{ banUserErrorMessage }}</p>
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Complaint from '../../models/Complaint.ts';
import ComplaintRepository from '../../repositories/ComplaintRepository';
import ComplaintSubject from '../../models/ComplaintSubject';
import UserBlockRepository from '../../repositories/UserBlockRepository';

const showBanUser = ref<boolean>(false);
const banUserSuccessMessage = ref<string | undefined>("");
const banUserErrorMessage = ref<string | undefined>("");
const test = ref<string | undefined>("");

const props = defineProps({
    complaint: Complaint
})
const emit = defineEmits(['relaodComplaints']);
const actionbarModel = ref<number>();

async function deleteComplaint() {
    let apiResponse = await ComplaintRepository.deleteComplaint(props.complaint?.idComplaint as number);
    if (!apiResponse.success) return;
    emit('relaodComplaints');
}
async function processComplaint() {
    let apiResponse = await ComplaintRepository.processComplaint(props.complaint?.idComplaint as number);
    if (!apiResponse.success) return;
    emit('relaodComplaints');
}
async function readComplaint() {
    let apiResponse = await ComplaintRepository.readComplaint(props.complaint?.idComplaint as number);
    if (!apiResponse.success) return;
    emit('relaodComplaints');
}

async function banUser() {
    if (test.value === "" || test.value ===undefined){
        banUserErrorMessage.value = "Veuillez entrer une raison";
        return;
    };

    let apiResponse = await UserBlockRepository.blockUser(test.value,props.complaint?.recipient.idAccount as number);
    if (!apiResponse.success) {
        banUserErrorMessage.value = apiResponse.errors![0].message;
        return;
    };
    banUserSuccessMessage.value = apiResponse.data!.message;

    await processComplaint();
}

function getType(subject: ComplaintSubject | undefined) {
    if (!subject) return '';
    if (subject.album !== undefined) return 'Album';
    if (subject.post !== undefined) return 'Post';
    if (subject.foto !== undefined) return 'Foto';
    return '';

}
</script>

<style scoped></style>