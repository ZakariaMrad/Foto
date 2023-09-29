<template>
    <v-card v-for="_ in 10" class="mx-auto ma-10" max-width="1200px">
        <v-list>
            <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" title="Sandra Adams"
                subtitle="sandra_a88@gmail.com"></v-list-item>
            <v-list-item>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem consequatur mollitia hic sequi, cum
                veritatis, illum, architecto quis quos eius eveniet exercitationem rem asperiores a. Qui facilis dolorem ea
                non.
            </v-list-item>
        </v-list>
        <v-container fluid>
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-img class="d-flex" height="600px" cover
                            src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                        </v-img>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>{{ nbLikes }}</v-list>
                            <!--TODO: Mettre une restriction de like a 1 par utilisateur par post-->
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-heart"
                                @click="nbLikes++"></v-btn>


                            <!--? Pas sur de vouloir avoir une option pour "dislikes"-->
                            <!--v-btn size="small" color="surface-variant" variant="text" icon="mdi-heart-broken"
                                @click="likes--"></v-btn-->

                            <v-list class="ml-2">{{ nbComment }}</v-list>
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

import { ref } from 'vue';

const nbLikes = ref(0);

//TODO: make a function to count numbers of comment 
const nbComment = 2;


</script>
