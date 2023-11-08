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

                            <v-list>{{ props.post?.likes }}</v-list>
                            <v-btn size="small" :color=" props.post?.isLiked ? 'red' : 'surface-variant'" variant="text" icon="mdi-heart"
                                @click="toggleLike()"></v-btn>

                            <v-list class="ml-2">{{ props.post?.comments }}</v-list>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-comment"></v-btn>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-share-variant"></v-btn>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-exclamation" @click="report()"/>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script setup lang="ts">
import Post from '../models/Post';
import ComplaintRepository from '../repositories/ComplaintRepository';

const props = defineProps({
    post: Post
})

function toggleLike() {
    if(!props.post)
        return;

    if (props.post.isLiked) {
        props.post.isLiked = false;
        props.post.likes--;
    } else {
        props.post.isLiked = true;
        props.post.likes++;
    }
}

function report() {

    ComplaintRepository.createComplaint(props.post?.idPost as number, 'post',props.post?.owner.idAccount as number )
    
}

</script>

<style scoped>


</style>