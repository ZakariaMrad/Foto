<template>
    <div>
        <v-row>
            <v-col cols="6">
                <v-card variant="outlined">
                    <v-card-title>
                        Plaintes non reçus
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item v-for="complaint in complaints.filter((c) => c.status !== Constants.STATUS_PROCESSED)">
                                <complaint :complaint="complaint" @relaodComplaints="reloadComplaints"/>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>

            </v-col>
            <v-col cols="6">
                <v-card variant="outlined">
                    <v-card-title>
                        Plaintes reçus
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item v-for="complaint in complaints.filter((c) => c.status === Constants.STATUS_PROCESSED)">
                                <complaint :complaint="complaint" @relaodComplaints="reloadComplaints"/>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Complaint from '../../models/Complaint';
import complaint from './complaint.vue';
import ComplaintRepository from '../../repositories/ComplaintRepository';
import Constants from '../../core/Constants';

const complaints = ref<Complaint[]>([]);
onMounted(async () => {
    await reloadComplaints();
})

async function reloadComplaints() {
    console.log('reload complaints');
        let apiResponse = await ComplaintRepository.getComplaints();
        if (!apiResponse.success || !apiResponse.data) return;
        complaints.value = apiResponse.data as Complaint[];
    console.log(complaints.value);
    
}
</script>

<style scoped></style>