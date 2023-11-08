<template>
    <PostComponent v-for="post in posts" :post="post" />
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import Post from '../models/Post';
import PostRepository from '../repositories/PostRepository';
import PostComponent from './PostComponent.vue'
import { Events, EventsBus } from '../core/EventBus';
const { bus } = EventsBus();

const posts = ref<Post[]>([])

onMounted(async () => {
    getPosts();
})

watch(() => bus.value.get(Events.CREATE_POST), async () => {
    getPosts();
})
watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), async () => {
    getPosts();
})

async function getPosts() {
    let apiResponse = await PostRepository.getPosts();
    if (!apiResponse.success) return;
    posts.value = apiResponse.data;
}


</script>
