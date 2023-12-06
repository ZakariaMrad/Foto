<template>
    <div class="d-flex align-center flex-column mt-3">
        <h1 class="text-h2 text-bold">Personnes suivi(s/es)</h1>
        <h3 class="text-bold"> Vous suivez 10 personnes</h3>
    </div>
    <v-card class="mx-auto ma-10" width="75%" height="100%">
        <v-card-text>
            <v-list lines="one">
                <v-list-item v-for="n in 10" :key="n">
                    <v-col cols="12">
                        <v-row>
                            <v-col cols="7">
                                <v-row>
                                    <v-avatar size="50">
                                        <v-img src="https://randomuser.me/api/portraits/women/85.jpg"
                                            alt="Sandra Adams"></v-img>
                                    </v-avatar>
                                    <v-list-item :title="'Profile name'" subtitle="exemple@exemple.com"></v-list-item>
                                </v-row>
                            </v-col>
                            <v-col cols="5">
                                <v-row>
                                    <v-list-item>120 publications</v-list-item>
                                    <v-list-item>752 Suiveurs</v-list-item>
                                    <v-list-item>1,5k Suivi</v-list-item>
                                    <v-list-item>
                                        <v-menu open-on-hover>
                                            <template v-slot:activator="{ props }">
                                                <v-btn color="primary" icon="mdi-dots-horizontal" v-bind="props">
                                                </v-btn>
                                            </template>

                                            <v-list>
                                                <v-list-item v-for="friendLink in friendLinks"
                                                    :prepend-icon="friendLink.icon" :key="friendLink.text"
                                                    @click="friendLink.click">
                                                    <v-list-item-title>{{ friendLink.text }}</v-list-item-title>
                                                </v-list-item>
                                            </v-list>
                                        </v-menu>

                                    </v-list-item>

                                </v-row>
                            </v-col>

                        </v-row>
                        <hr class="mx-auto" width="75%">
                    </v-col>

                </v-list-item>
            </v-list>

        </v-card-text>

    </v-card>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import Account from '../models/Account';
import { EventsBus, Events } from '../core/EventBus';

const { eventBusEmit, bus } = EventsBus();
const connectedAccount = ref<Account>()

const friendLinks = ref<{ icon: string, text: string, click: any }[]>(
    [
        { icon: 'mdi-trash-can-outline', text: 'Arreter de suivre', click: openUnfollowModal },
    ]
)

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account: Account[] | undefined) => {
    if (!account)
        return;

    connectedAccount.value = account[0];
})

onMounted(async () => {
    //    TODO: mettre la liste damis
    // console.log(connectedAccount.value?.friendList);
})

function openUnfollowModal() {
    eventBusEmit(Events.OPEN_UNFOLLOW_MODAL)
}
</script>