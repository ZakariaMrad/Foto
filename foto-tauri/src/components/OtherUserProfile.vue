<template>
    <v-container>
        <v-row justify="center" no-gutters style="height: auto">
            <v-col cols="2">
                <v-sheet class="pa-2 ma-2">

                    <v-avatar size="150">
                        <v-img :src="account?.picturePath" alt="Sandra Adams"></v-img>
                    </v-avatar>
                </v-sheet>
            </v-col>
            <v-col cols="5">
                <v-row cols="12" class="pa-2">
                    <v-col cols="6">
                        <h2 colors="grey">{{ account?.name }}</h2>
                        <p class="font-italic">{{ account?.email }}</p>
                    </v-col>
                    <v-col cols="6" class="text-lg-right">
                        <v-btn @click="btnSuivreProfil(account!)">Suivre</v-btn>
                    </v-col>
                </v-row>
                <v-row cols="12" class="pa-3 font-weight-bold">
                    <v-col cols="4">
                        <p>
                            {{ posts.filter((post: Post) => post.owner.idAccount == account?.idAccount).length }} Publications
                        </p>
                    </v-col>
                    <v-col cols="4">
                        <p>
                            752 Suiveurs
                        </p>
                    </v-col>
                    <v-col cols="4">
                        <p>
                            {{ account?.friends.length }} Suivis
                        </p>
                    </v-col>
                </v-row>
                <v-sheet class="pa-2 ma-2">
                    {{ }}
                </v-sheet>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="8">
                <hr>
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="10">
                <v-tabs color="deep-purple-accent-4" align-tabs="center">
                    <v-tab :value="1">Photos</v-tab>
                    <v-tab :value="2">Albums</v-tab>
                </v-tabs>
                <v-window>
                    <v-window-item v-for="n in 3" :key="n" :value="n">
                        <v-container fluid>
                            <v-row>
                                <v-col v-for="post in posts" cols="12" md="4">
                                    <v-img @click="openPostModal(post.idPost)"

                                        v-if="post.owner.idAccount == account?.idAccount"
                                        :src="`${post.foto.path}`" 
                                        :style="{filter: 'saturate(' + post.foto.saturation +'%) contrast(' + post.foto.contrast +'%) brightness(' + post.foto.exposition +'%)'}"

                                        aspect-ratio="2"></v-img>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-window-item>
                </v-window>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import PostRepository from '../repositories/PostRepository';
import Post from '../models/Post';
import Account from '../models/Account';
import { EventsBus, Events } from '../core/EventBus';
import AccountRepository from '../repositories/AccountRepository';
import { useRoute } from 'vue-router';


const { eventBusEmit, bus } = EventsBus();


const idAccount = ref<string>();

const posts = ref<Post[]>([])
const account = ref<Account>()
const connectedAccount = ref<Account>()






onMounted(async () => {
    const param = useRoute().params.idAccount;
    idAccount.value = param.toString();

    if (!idAccount) {
        console.log(account.value);
        return;
    }

    let apiResponse = await AccountRepository.getOtherUserAccount(parseInt(idAccount.value));
    if (!apiResponse.success) return;
    account.value = apiResponse.data;

    // console.log("ID ACCOUNT: " + idAccount.value);


    let apiPostResponse = await PostRepository.getPosts();
    if (!apiPostResponse.success) return;
    posts.value = apiPostResponse.data;
    posts.value = posts.value.filter((post: Post) => {
        if (post.foto)
            return post;
        else
            return;
    });
})

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account: Account[] | undefined) => {
    if (!account)
        return;

    connectedAccount.value = account[0];
})


function openPostModal(idPost: number) {
    eventBusEmit(Events.OPEN_POST_MODAL, idPost)
}

async function btnSuivreProfil(friendAccount: Account) {
    console.log(friendAccount);
    if (!idAccount)
        return;

    let apiResponse = await AccountRepository.follow(friendAccount);
    console.log(apiResponse);
    
    if (!apiResponse.success) return;
}

</script>

