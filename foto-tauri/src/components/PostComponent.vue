<template>
    <v-card class="mx-auto ma-10" max-width="1200px">
        <v-list>
            <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" :title="props.post?.owner.name"
                :subtitle="props.post?.owner.email" @click="openUserProfile(props.post?.owner.idAccount)"></v-list-item>
            <v-list-item>
                {{ props.post?.description }}
            </v-list-item>
        </v-list>
        <v-container fluid>
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-img class="d-flex" cover :src="props.post?.foto.path">
                        </v-img>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>{{ props.post?.likes }}</v-list>
                            <v-btn size="small" :color="props.post?.isLiked ? 'red' : 'surface-variant'" variant="text"
                                icon="mdi-heart" @click="toggleLike()"></v-btn>

                            <v-list class="ml-2">{{ props.post?.comments }}</v-list>
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
import { EventsBus, Events } from '../core/EventBus';

const { eventBusEmit } = EventsBus();


const props = defineProps({
    post: Post
})

function openUserProfile(idAccount: number) {
    // console.log(idAccount);

    eventBusEmit(Events.OPEN_USER_PROFILE, idAccount)
}

function toggleLike() {
    if (!props.post)
        return;

    if (props.post.isLiked) {
        props.post.isLiked = false;
        props.post.likes--;
    } else {
        props.post.isLiked = true;
        props.post.likes++;
    }
}
</script>

<style scoped></style>