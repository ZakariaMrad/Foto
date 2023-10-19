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
                            <v-list-item>
                                <complaint />
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>

            </v-col>
            <v-col cols="7">
                <v-card>lsndfslkfd</v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import complaint from './complaint.vue';

</script>

<style scoped></style>