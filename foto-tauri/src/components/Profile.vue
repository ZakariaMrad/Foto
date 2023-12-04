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
                        <h2 colors="grey">{{ connectedAccount?.name }}</h2>
                        <p class="font-italic">{{ connectedAccount?.email }}</p>
                    </v-col>
                    <v-col cols="6" class="text-lg-right">


                        <v-menu open-on-hover>
                            <template v-slot:activator="{ props }">
                                <v-btn color="primary" icon="mdi-dots-horizontal" v-bind="props">
                                </v-btn>

                                <v-btn class="ms-2" color="red-darken-3" v-if="isAdmin"
                                    @click="openAdminPage()">Admin</v-btn>

                            </template>

                            <v-list>
                                <v-list-item v-for="profileLink in profileLinks" :prepend-icon="profileLink.icon"
                                    :key="profileLink.text" @click="profileLink.click">
                                    <v-list-item-title>{{ profileLink.text }}</v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

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
                    {{ connectedAccount?.bio }}
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
                <v-tabs color="deep-purple-accent-4" align-tabs="center" v-model="tab">
                    <v-tab value="posts">Publications</v-tab>
                    <v-tab value="albums">Albums</v-tab>
                    <v-tab value="fotos">Portefolio</v-tab>
                </v-tabs>
                <v-window v-model="tab">
                    <v-window-item key="1" value="posts">
                        <v-container fluid>
                            <v-row>
                                <v-col v-for="post in posts" cols="12" md="4">
                                    <v-img @click="openPostModal(post.idPost)"
                                        v-if="post.owner.idAccount == connectedAccount?.idAccount"
                                        :src="`${post.foto.path}`" aspect-ratio="2"></v-img>
                                </v-col>
                            </v-row>
                        </v-container>
                    </v-window-item>

                    <v-window-item key="2" value="albums">
                        <v-container fluid class="mt-3">
                            <v-row justify="center">
                                <v-btn prepend-icon="mdi-plus-box" @click="createAlbum">
                                    Créer un album
                                </v-btn>
                            </v-row>
                            <AlbumList/>
                        </v-container>
                    </v-window-item>

                    <v-window-item key="3" value="fotos">
                        <v-container fluid class="mt-3">
                            <v-row justify="center">
                                <v-btn prepend-icon="mdi-plus-box" @click="createPost">
                                    Créer une publication
                                </v-btn>
                            </v-row>
                            <AssetLister :items="fotos" title=""/>
                        </v-container>
                    </v-window-item>
                </v-window>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup lang="ts">
import { watch, ref, onMounted } from 'vue';
import { EventsBus, Events } from '../core/EventBus';
import Account from '../models/Account';
import AccountRepository from '../repositories/AccountRepository';
import Post from '../models/Post';
import PostRepository from '../repositories/PostRepository';
import FotoRepository from '../repositories/FotoRepository';
import Foto from '../models/Foto';
import AssetLister from './AssetLister.vue';
import { useRoute } from 'vue-router';
import AlbumList from './AlbumList.vue';

const { eventBusEmit, bus } = EventsBus();

const connectedAccount = ref<Account>()
const posts = ref<Post[]>([])
const isAdmin = ref<boolean>(false)
const fotos = ref<Foto[]>([]);

const tab = ref<string>();

onMounted( async () => {
    const query = useRoute().query;
    console.log(query);
    if (query.tab) {
        console.log(query.tab);
        if (query.tab === "fotos")
            tab.value = query.tab;
    }

    let apiResponse = await AccountRepository.isAdmin()
    console.log(apiResponse);
    if (apiResponse.success)
        isAdmin.value = apiResponse.data
    
    let apiPostResponse = await PostRepository.getPosts();
    if (apiPostResponse.success) {
        posts.value = apiPostResponse.data;
        posts.value = posts.value.filter((post: Post) => {
        if (post.foto)
            return post;
        else
            return;
        });
    }

    await Promise.all([getFotos()]);
});

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account: Account[] | undefined) => {
    if (!account)
        return;

    connectedAccount.value = account[0];
})

// watch(() => tab.value, (value) => {
//     console.log("TEST TAB = " + value);
// })


const profileLinks = ref<{ icon: string, text: string, click: any }[]>(
    [
        { icon: 'mdi-pencil-outline', text: 'Modifier le profil', click: openProfileModificationModal },
    ]
)

async function openAdminPage() {
    let apiResponse = await AccountRepository.isAdmin()
    if (!apiResponse.success) return;
    isAdmin.value = apiResponse.data
    eventBusEmit(Events.OPEN_ADMIN_PANEL)
}

function openProfileModificationModal() {
    eventBusEmit(Events.OPEN_MODIFY_PROFILE_MODAL)
}

function openPostModal(idPost : number) {
    eventBusEmit(Events.OPEN_POST_MODAL, idPost)
}

function createPost() {
    eventBusEmit(Events.CREATE_POST)
}
function createAlbum() {
    eventBusEmit(Events.CREATE_ALBUM)
}

async function getFotos() {
    let apiResponse = await FotoRepository.getFotos()
    if (!apiResponse.success) return
    fotos.value = apiResponse.data
}
</script>

<style scoped>
img:hover {
    cursor: pointer;
}
</style>