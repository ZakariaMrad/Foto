<template>
    <v-card variant="outlined" class="pa-2">

        <v-row class="ma-1">
            <v-col cols="4">
                <v-row class="p-1">
                    Envoyé par <span class="ms-1 text-green">Zakaria</span>
                </v-row>
                <v-row class="p-1">
                    Envers <span class="ms-1 text-red-darken-3">Zakaria</span>
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
                            <v-icon color="red-darken-3" size="large" icon="mdi-delete"/>
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

const props = defineProps({
    complaint: Complaint
})
const actionbarModel = ref<number>();

let icons = new Map([
    ["unread", { icon: "mdi-exclamation-thick", color: "red-darken-2" }],
    ["unresolved", { icon: "mdi-exclamation", color: "indigo" }],
    ["resolved", { icon: "mdi-check", color: "green" }],
]);
</script>

<style scoped></style>