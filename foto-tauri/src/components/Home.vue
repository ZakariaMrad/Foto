<template>
    <v-col class="d-flex justify-end">
        <!-- i nned to put the btn-toggle at the end of the row -->

        <v-btn-toggle v-model="columnChoice" shaped mandatory>
            <v-btn icon="mdi-tally-mark-1" />
            <v-btn icon="mdi-tally-mark-2" />
            <v-btn icon="mdi-tally-mark-3" />
        </v-btn-toggle>
    </v-col>
    <v-col>
        <v-row>
            <v-col v-for="post in posts" :cols="columnNumber[columnChoice]">
                <PostComponent :post="post" />
            </v-col>
        </v-row>à
    </v-col>
    <!-- <PostComponent v-for="post in posts" :post="post" /> -->

</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import Post from '../models/Post';
import PostRepository from '../repositories/PostRepository';
import PostComponent from './PostComponent.vue'
import { Events, EventsBus } from '../core/EventBus';
const { bus } = EventsBus();

const posts = ref<Post[]>([])
const columnChoice = ref<number>(0)
const columnNumber = ref<number[]>([12, 6, 4])

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
