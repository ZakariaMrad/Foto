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
                        <h2 colors="grey">{{ }}</h2>
                        <p class="font-italic">{{ }}</p>
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
                                <v-col v-for="i in 34" :key="i" cols="12" md="4">
                                    <v-img :src="`https://picsum.photos/500/300?image=${i * n * 5 + 10}`"
                                        :lazy-src="`https://picsum.photos/10/6?image=${i * n * 5 + 10}`"
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
import { watch } from 'vue';
import { EventsBus, Events } from '../core/EventBus';
// import Account from '../models/Account';
// import AccountRepository from '../repositories/AccountRepository';


const { bus } = EventsBus();

// let idAccountPasse: number;
// const idAccount = ref<Account>()

watch(() => bus.value.get(Events.OPEN_USER_PROFILE), idAccountPasse => {
    console.log("je suis rentrer dans le watch")
    // if (!account)
    //     return;

        // idAccount.value? = idAccountPasse;
        console.log(idAccountPasse);
        
})

// import { watch, ref, onMounted } from 'vue';
// import { EventsBus, Events } from '../core/EventBus';
// import Account from '../models/Account';
// import AccountRepository from '../repositories/AccountRepository';

// const { bus } = EventsBus();

// const otherUserProfile = ref<Account>()
// const isAdmin = ref<boolean>(false)

// onMounted(async () => {
//     let apiResponse = await AccountRepository.isAdmin()
//     if (!apiResponse.success) return;
//     isAdmin.value = apiResponse.data
// })

// otherProfile(async () => {
//     let apiResponse = await AccountRepository.getOtherUserAccount()
//     if(!apiResponse.success) return;

// })

// watch(() => bus.value.get(Events.GET_OTHER_USER_PROFILE), (account: Account[] | undefined) => {
//     if (!account)
//         return;

//         otherUserProfile. = account[0];
// })








</script>

