<template>
    <v-card>
        <v-list>
            <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" :title="account?.name"
                :subtitle="account?.email"></v-list-item>
            <v-list-item>
                {{album.title}}
            </v-list-item>
        </v-list>
        <v-container fluid>
            <v-row dense>
                <v-col>
                    <v-card>
                        <!-- <v-img class="d-flex" height="600px" cover
                            src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                        </v-img> -->
                        <v-carousel hide-delimiters height="100%">
                            <v-carousel-item v-for="(foto, i) in album.fotos" :key="i-1">
                                <v-img :src="foto.path" aspect-ratio="3"></v-img>
                            </v-carousel-item>
                        </v-carousel>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>0</v-list>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-heart"></v-btn>
                            <v-list class="ml-2">0</v-list>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-comment"></v-btn>

                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-share-variant"></v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script setup lang="ts">
import Account from '../../models/Account';
import Album from '../../models/Album';


defineProps<{
    account:Account | undefined,
    album:Partial<Album>
}>()
</script>

<style scoped>

</style>