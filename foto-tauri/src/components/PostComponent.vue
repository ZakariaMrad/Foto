<template>
    <v-card class="mx-auto ma-10" max-width="1200px">
        <v-list>
            <v-list-item :prepend-avatar="props.post?.owner.picturePath" :title="props.post?.owner.name"
                :subtitle="props.post?.owner.email" @click="openUserProfile(props.post?.owner.idAccount!)"></v-list-item>
            <v-list-item>
                {{ props.post?.description }}
            </v-list-item>
        </v-list>
        <v-container fluid v-if="props.post?.foto">
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-img class="d-flex" cover :src="props.post?.foto.path"
                        :style="{filter: 'saturate(' + props.post?.foto.saturation +'%) contrast(' + props.post?.foto.contrast +'%) brightness(' + props.post?.foto.exposition +'%)'}"
                            >
                        </v-img>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>{{ likes }}</v-list>

                            <v-btn size="small" :color="props.post?.isLiked ? 'red' : 'surface-variant'" variant="text"
                                icon="mdi-heart" @click="toggleLike()"></v-btn>

                            <v-list class="ml-2">{{ props.post?.comments.length }}</v-list>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-comment" @click="openComments()"></v-btn>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-share-variant"></v-btn>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-exclamation" @click="report()"/>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
        <v-container fluid v-else>
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-carousel hide-delimiters height="100%" v-if="props.post?.album.type === 'carousel'">
                            <v-carousel-item v-for="(foto, i) in props.post?.album.fotos" :key="i - 1">
                                <v-img :src="foto.path" aspect-ratio="3" 
                                :style="{filter: 'saturate(' + foto.saturation +'%) contrast(' + foto.contrast +'%) brightness(' + foto.exposition +'%)'}"
                            ></v-img>
                            </v-carousel-item>
                        </v-carousel>
                        <v-table v-else>
                            <tbody>
                                <tr v-for="(_, y) in props.post?.album.grid?.nbRows">
                                    <td v-for="(_, x) in props.post?.album.grid?.nbCols" class="pa-1">
                                        <v-responsive aspect-ratio="1"
                                            v-if="(y * props.post?.album.grid!.nbCols! + x) < props.post?.album.grid?.fotosPosition.length!">
                                            <v-card height="100%">
                                                <v-img :src="getPath(y * props.post?.album.grid!.nbCols! + x)" aspect-ratio="1" 
                                                :style="{filter: 'saturate(' + getFoto(y * props.post?.album.grid!.nbCols! + x)?.saturation +'%) contrast(' + getFoto(y * props.post?.album.grid!.nbCols! + x)?.contrast +'%) brightness(' + getFoto(y * props.post?.album.grid!.nbCols! + x)?.exposition +'%)'}"
                            />
                                            </v-card>
                                        </v-responsive>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-list>{{ likes }}</v-list>

                            <v-btn size="small" :color="props.post?.isLiked ? 'red' : 'surface-variant'" variant="text"
                                icon="mdi-heart"></v-btn>
                            <v-list class="ml-2">{{ props.post?.comments.length }}</v-list>
                            <v-btn size="small" color="surface-variant" variant="text" icon="mdi-comment" @click="openComments()"></v-btn>
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
import { ref, onMounted } from 'vue';
import Post from '../models/Post';
import { EventsBus, Events } from '../core/EventBus';
import ComplaintRepository from '../repositories/ComplaintRepository';
import AccountRepository from '../repositories/AccountRepository';
import Like from '../models/Like';
import LikeRepository from '../repositories/LikeRepository';

const { eventBusEmit } = EventsBus();

const props = defineProps({
    post: Post
})

const likes = ref<number>(0);

const getPath = (index: number) => {
    if (!props.post?.album) return;
    const id = props.post?.album.grid!.fotosPosition[index];
    return props.post?.album.fotos!.find(foto => foto.idFoto === id)?.path;
}

const getFoto = (index: number) => {
    if (!props.post?.album) return;
    const id = props.post?.album.grid!.fotosPosition[index];
    return props.post?.album.fotos!.find(foto => foto.idFoto === id);
}

onMounted(() => {
    if (!props.post) return;
    likes.value = props.post?.likes.length;
});

function openUserProfile(idAccount: number) {

    eventBusEmit(Events.OPEN_USER_PROFILE, idAccount)
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

function openComments() {
    eventBusEmit(Events.OPEN_COMMENTS, props.post?.idPost);
}

function report() {
    ComplaintRepository.createComplaint(props.post?.idPost as number, 'post',props.post?.owner.idAccount as number )
}

</script>

<style scoped></style>