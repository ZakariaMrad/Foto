<template>
    <div>
        <v-row>
            <v-col cols="5">
                <v-card variant="outlined">
                    <v-card-title>
                        Plaintes non reçus
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item v-for="complaint in complaints.filter((c) => c.state === 'unread')">
                                <complaint :complaint="complaint" />
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>

            </v-col>
            <v-col cols="7">
                <v-card variant="outlined">
                    <v-card-title>
                        Plaintes reçus
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item v-for="complaint in complaints.filter((c) => c.state !== 'unread')">
                                <complaint :complaint="complaint" />
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import Complaint from '../../models/Complaint';
import complaint from './complaint.vue';
const complaints = [
    new Complaint(1, 1, 'unread', 'Foto incorrecte', 'une foto n\'est pas permise'),
    new Complaint(2, 1, 'unread', 'Foto incorrecte', 'une foto n\'est pas permise'),
    new Complaint(3, 1, 'unread', 'Album non permis', 'l\'album n\'est pas permis'),
    new Complaint(3, 1, 'unresolved', 'Album non permis', 'l\'album n\'est pas permis'),
    new Complaint(3, 1, 'unresolved', 'Album non permis', 'l\'album n\'est pas permis'),
    new Complaint(3, 1, 'resolved', 'Album non permis', 'l\'album n\'est pas permis'),
    new Complaint(3, 1, 'resolved', 'Album non permis', 'l\'album n\'est pas permis'),
    new Complaint(3, 1, 'resolved', 'Album non permis', 'l\'album n\'est pas permis'),
]
</script>

<style scoped></style>