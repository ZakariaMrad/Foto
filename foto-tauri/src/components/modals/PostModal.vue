<template>
    <v-dialog width="50%" v-model="props.activate">
        <v-card title="Info Post 😍">
            <v-card-text>
                <div v-for="post in posts">
                    <div v-if="post.idPost == props.idPost">
                        <h4>{{ post.description }}</h4>
                        <h5>Publiée le {{ post.creationDate.date }}</h5>
                        <hr>
                        <img class="w-100" :src="`${post.foto.path}`" alt=""
                        :style="{filter: 'saturate(' + post.foto.saturation +'%) contrast(' + post.foto.contrast +'%) brightness(' + post.foto.exposition +'%)'}"
                            >
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>{{ likes }}</v-list>
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
import Like from '../../models/Like';
import AccountRepository from '../../repositories/AccountRepository';
import LikeRepository from '../../repositories/LikeRepository';

const emit = defineEmits(['closeDialog'])
const posts = ref<Post[]>([])
const likes = ref<number>(0);


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
    if (!props.post) return;
    likes.value = props.post?.likes.length;

})


function closeDialog() {
    emit('closeDialog');
}

async function toggleLike() {
    if (!props.post) return;
    if (!props.post.isLiked) {
        let result = await AccountRepository.getAccount(); 
        if (!result.success) return;
        const account = result.data;

        const like =  new Like();
        like.post = props.post;
        like.user = account;
        let apiResult = await LikeRepository.uploadLike(like);
        if (!apiResult.success) {
            console.log("LIKE HAS FAILED");
            return
        }
        props.post.isLiked = true;
        likes.value++;
    } else if (props.post.isLiked) {
        console.log("DELETE LIKE");
        let result = await AccountRepository.getAccount(); 
        if (!result.success) return;
        const account = result.data;

        const likeResult = await LikeRepository.getLike(account.idAccount, props.post.idPost);
        if (!likeResult.success) return;
        const like = likeResult.data?.idLike;
        if (!like) return;

        const deleteResult = await LikeRepository.deleteLike(like);
        if (!deleteResult.success) {
            console.log("DELETE LIKE HAS FAILED");
            return
        }
        props.post.isLiked = false;
        likes.value--;
    }
}
</script>

<style scoped></style>