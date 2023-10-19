<template>
    <v-card class="mx-auto ma-10" max-width="1200px">
        <v-list>
            <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" :title="props.post?.owner.name"
                :subtitle="props.post?.owner.email"></v-list-item>
            <v-list-item>
                {{ props.post?.description  }}
            </v-list-item>
        </v-list>
        <v-container fluid>
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-img class="d-flex" cover
                            :src="props.post?.foto.path">
                        </v-img>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <!-- <v-list>{{ nbLikes }}</v-list> -->
                            <!--TODO: Mettre une restriction de like a 1 par utilisateur par post-->
                            <!-- <v-btn size="small" color="surface-variant" variant="text" icon="mdi-heart"
                                @click="nbLikes++"></v-btn> -->


                            <!--? Pas sur de vouloir avoir une option pour "dislikes"-->
                            <!--v-btn size="small" color="surface-variant" variant="text" icon="mdi-heart-broken"
                                @click="likes--"></v-btn-->

                            <!-- <v-list class="ml-2">{{ nbComment }}</v-list> -->
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
import Post from '../models/Post';

const props = defineProps({
    post: Post
})
</script>

<style scoped></style>