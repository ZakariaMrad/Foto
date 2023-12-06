<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card title="Info Post 😍">
            <v-card-text>
                <div v-for="post in posts">
                    <div v-if="post.idPost == props.idPost">
                        <h4>{{ post.description }}</h4>
                        <hr>
                        <img class="w-100" :src="`${post.foto.path}`" alt=""
                        :style="{filter: 'saturate(' + post.foto.saturation +'%) contrast(' + post.foto.contrast +'%) brightness(' + post.foto.exposition +'%)'}"
                            >
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>{{ post.likes.length }}</v-list>
                            <v-btn size="small" :color="post?.isLiked ? 'red' : 'surface-variant'" variant="text"
                                icon="mdi-heart" @click="toggleLike()"></v-btn>

                            <v-list class="ml-2">{{ post.comments.length }}</v-list>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-comment"></v-btn>

                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-share-variant"></v-btn>
                        </v-card-actions>
                    </div>

                </div>
                <v-card-actions>
                    <v-spacer />
                    <v-btn text="Fermer" @click="closeDialog" />
                </v-card-actions>

            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import Post from '../../models/Post';
import PostRepository from '../../repositories/PostRepository';

const emit = defineEmits(['closeDialog'])
const posts = ref<Post[]>([])


const props = defineProps({
    activate: Boolean,
    idPost: Number,
    post: Post
})

console.log("test2", props.idPost);
onMounted(async () => {

    let apiPostResponse = await PostRepository.getPosts();
    if (!apiPostResponse.success) return;
    posts.value = apiPostResponse.data;

})


function closeDialog() {
    emit('closeDialog');
}

function toggleLike() {
}
</script>

<style scoped></style>