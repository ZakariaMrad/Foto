<template>
    <v-card variant="outlined" class="pa-2">

        <v-row>
            <v-col cols="2">
                <v-icon :icon="icons.get(props.complaint!.state)?.icon" :color="icons.get(props.complaint!.state)?.color"></v-icon>
            </v-col>
            <v-col cols="5">
                {{ props.complaint?.title }}
            </v-col>
            <v-col cols="5">
                {{ props.complaint?.description }}
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import Complaint from '../../models/Complaint';

const props = defineProps({
    complaint: Complaint
})

let icons = new Map([
    ["unread", { icon: "mdi-exclamation-thick", color: "red-darken-2" }],
    ["unresolved", { icon: "mdi-exclamation", color: "indigo" }],
    ["resolved", { icon: "mdi-check", color: "green" }],
    ]);
</script>

<style scoped></style>