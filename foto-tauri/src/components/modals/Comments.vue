<template>
    <v-dialog width="30%" v-model="props.activate">
        <v-card>
            <v-btn style="position: absolute; top:5px; right: 5px; z-index: 10;" class="toolbar-btn" variant="tonal"
                color="black" icon="$close" @click="closeDialog()"></v-btn>
            <v-list>
                <v-list-item v-for="comment in comments" :prepend-avatar="comment.user.picturePath">
                    <v-list-item-title>
                        <small class="text-muted">
                            {{ comment.user.email }}
                        </small>
                    </v-list-item-title>
                    <p>{{ comment.text }}</p>
                    <v-divider></v-divider>
                </v-list-item>
            </v-list>
            <v-card-actions>
                <v-container>
                    <v-row>
                        <v-avatar style="margin-top: 10px; margin-right: 10px; margin-left: 10px;">
                            <v-img :src="connectedAccount?.picturePath"></v-img>
                        </v-avatar>
                        <v-text-field label="Laisser un commentaire..." v-model="commentText"></v-text-field>
                        <v-btn icon="mdi-send" style="margin-top: 5px; transform: rotate(-25deg);"
                            @click="postComment()"></v-btn>
                    </v-row>
                </v-container>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">

import { onMounted, ref } from 'vue';
import Comment from '../../models/Comment';
import AccountRepository from '../../repositories/AccountRepository';
import PostRepository from '../../repositories/PostRepository';
import CommentRepository from '../../repositories/CommentRepository';
import Account from '../../models/Account';

onMounted(async () => {
    await Promise.all([getComments()]);
});

var comments = ref<Comment[]>([]);
const commentText = ref("");
const emit = defineEmits(['closeDialog'])
const connectedAccount = ref<Account>()

const props = defineProps({
    activate: Boolean,
    idPost: Number
});

function closeDialog() {
    emit('closeDialog');
}

async function getComments() {
    if (!props.idPost) return;
    let apiResponse = await PostRepository.getPostById(props.idPost);
    if (!apiResponse.success) return;
    console.log("COMMENTS" + props.idPost);
    console.log(apiResponse.data);
    const jsonObject = apiResponse.data.comments as Object[];
    comments.value = Object.assign(Array<Comment>(), jsonObject)
}

onMounted(async () => {
    let result = await AccountRepository.getAccount();
    if (!result.success) return;
    connectedAccount.value = result.data;
})

async function postComment() {
    let result = await AccountRepository.getAccount();
    if (!result.success) return;
    const account = result.data;
    if (!props.idPost || commentText.value.length === 0) {
        return;
    }

    let apiResponse = await PostRepository.getPostById(props.idPost);
    if (!apiResponse.success) return;

    const comment = new Comment();
    comment.post = apiResponse.data;
    comment.user = account;
    comment.text = commentText.value;

    console.log("ETEST");
    let apiResult = await CommentRepository.uploadComments(comment);
    if (!apiResult.success) {
        console.log("COMMENTS BOOO");
        return
    }
    commentText.value = "";
    await getComments();
}

</script>

<style scoped></style>