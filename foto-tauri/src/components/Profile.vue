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
                        <h2 colors="grey">{{connectedAccount?.name}}</h2>
                    </v-col>
                    <v-col cols="6" class="text-lg-right">
                        <v-btn>Follow</v-btn>
                    </v-col>
                    <v-col cols="12">
                        <p>{{connectedAccount?.email}}</p>
                    </v-col>
                </v-row>
                <v-row cols="12" class="pa-3 font-weight-bold">
                    <v-col cols="4">
                        <p>
                            120 posts
                        </p>
                    </v-col>
                    <v-col cols="4">
                        <p>
                            752 followers
                        </p>
                    </v-col>
                    <v-col cols="4">
                        <p>
                            1,5K following
                        </p>
                    </v-col>
                </v-row>
                <v-sheet class="pa-2 ma-2">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Pariatur molestias, obcaecati fugiat unde
                    aliquid illum quibusdam quo error similique quae sit aut placeat doloremque ipsam? Ipsa delectus ipsum
                    repellendus nihil.
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
import { watch, ref } from 'vue';
import { EventsBus, Events } from '../core/EventBus';
import { Account } from '../models/Account';

const { bus } = EventsBus();

const connectedAccount = ref<Account>() 

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account: Account[] | undefined) => {
    if (!account)
        return;

    connectedAccount.value = account[0];
})

</script>