<template v-if="renderComponent">
    <div class="d-flex align-center flex-column mt-3">
        <h1 class="text-h2 text-bold">Personnes suivi(s/es)</h1>
        <h3 v-if="connectedAccount?.friends.length! <= 1" class="text-bold"> Vous suivez {{ connectedAccount?.friends.length }} personne</h3>
        <h3 v-if="connectedAccount?.friends.length! > 1" class="text-bold"> Vous suivez {{ connectedAccount?.friends.length }} personnes</h3>
    
    </div>
    <v-card class="mx-auto ma-10" width="75%" height="100%">
        <v-card-text>
            <v-list lines="one">
                <v-list-item v-for="friend in connectedAccount?.friends">
                    <v-col cols="12">
                        <v-row>
                            <v-col cols="7">
                                <v-row>
                                    <v-avatar size="50">
                                        <v-img :src="friend?.picturePath"
                                            alt="Sandra Adams"></v-img>
                                    </v-avatar>
                                    <v-list-item :title="friend.name" :subtitle="friend.email"></v-list-item>
                                </v-row>
                            </v-col>
                            <v-col cols="5">
                                <v-row>
                                    <v-list-item>120 publications</v-list-item>
                                    <v-list-item>752 Suiveurs</v-list-item>
                                    <v-list-item>1,5k Suivi</v-list-item>
                                    <v-list-item>
                                        <v-btn color="primary" @click="openUnfollowModal(friend)">
                                            Unfollow
                                        </v-btn>
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
import { ref, onMounted, watch, nextTick } from 'vue';
import Account from '../models/Account';
import { EventsBus, Events } from '../core/EventBus';
import AccountRepository from '../repositories/AccountRepository';

const { bus } = EventsBus();
const connectedAccount = ref<Account>()

const renderComponent = ref(true);

const forceRerender = async () => {

    // Here, we'll remove MyComponent
    renderComponent.value = false;
    // Then, wait for the change to get flushed to the DOM
    await nextTick();
    // Add MyComponent back in
    renderComponent.value = true;
}

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (account: Account[] | undefined) => {
    if (!account)
        return;

    connectedAccount.value = account[0];
})

onMounted(async () => {
})

async function openUnfollowModal(friend: Account) {
    let apiResponse = await AccountRepository.unfollow(friend)
    if (!apiResponse.success) {
        forceRerender();
        // return;
    }

    // eventBusEmit(Events.OPEN_UNFOLLOW_MODAL)
}

</script>