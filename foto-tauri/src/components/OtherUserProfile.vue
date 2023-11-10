<template>
    <v-container>
        <v-row justify="center" no-gutters style="height: auto">
            <v-col cols="2">
                <v-sheet class="pa-2 ma-2">

                    <v-avatar size="150">
                        <v-img src="https://randomuser.me/api/portraits/women/85.jpg" alt="Sandra Adams"></v-img>
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
                        <v-btn>Follow</v-btn>
                    </v-col>
                </v-row>
                <v-row cols="12" class="pa-3 font-weight-bold">
                    <v-col cols="4">
                        <p>
                            120 Publications
                        </p>
                    </v-col>
                    <v-col cols="4">
                        <p>
                            752 Suiveurs
                        </p>
                    </v-col>
                    <v-col cols="4">
                        <p>
                            1,5K Suivis
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
                                        :src="`${post.foto.path}`" aspect-ratio="2"></v-img>
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
// import AccountRepository from '../repositories/AccountRepository';
import PostRepository from '../repositories/PostRepository';
import Post from '../models/Post';
import Account from '../models/Account';
import { EventsBus, Events } from '../core/EventBus';
import AccountRepository from '../repositories/AccountRepository';


const { eventBusEmit } = EventsBus();

const props = defineProps({
    idAccount: Number,
    post: Post,
    // accounts: Account
})


const posts = ref<Post[]>([])
const account = ref<Account>()

onMounted(async () => {
    console.log("le test", props.idAccount);

    if (!props.idAccount) {
        console.log(account.value);
        return;
    }



    let apiResponse = await AccountRepository.getOtherUserAccount(props.idAccount);
    if (!apiResponse.success) return;
    account.value = apiResponse.data;

    console.log(account.value);


    let apiPostResponse = await PostRepository.getPosts();
    if (!apiPostResponse.success) return;
    posts.value = apiPostResponse.data;
})

watch(() => (props.idAccount), async (value: number | undefined) => {
    if (!value) {
        return;
    }

    let apiResponse = await AccountRepository.getOtherUserAccount(value);
    if (!apiResponse.success) return;
    account.value = apiResponse.data;

    console.log(account.value.idAccount);
    
})

function openPostModal(idPost : number) {
    eventBusEmit(Events.OPEN_POST_MODAL, idPost)
}

// accounts.forEach(element => {
//     if(otherUserAccount.value?.idAccount === props.idAccount){

//     }
// });




// function getUserAccount() {
//     let apiAccountResponse = await AccountRepository.getOtherUserAccount(props.idAccount)
//     accounts.value = apiAccountResponse.data[0];

// }

</script>

