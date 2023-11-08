<template>
    <div>
        <v-row>
            <v-col cols="5">
                <v-card variant="outlined">
                    <v-card-title>
                        Utilisateurs non bloqués
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item>

                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>

            </v-col>
            <v-col cols="7">
                <v-card variant="outlined">
                    <v-card-title>
                        Utlisateurs bloqués
                    </v-card-title>
                    <v-card-text>
                        <v-list>
                            <v-list-item v-for="account in accounts">
                                <account :account="account"/>
                            </v-list-item>
                        </v-list>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import AccountRepository from '../../repositories/AccountRepository';
import Account from '../../models/Account';
import account from './account.vue';

const accounts = ref<Account[]>([])

onMounted(async () => {
    console.log('search user');
    
    let apiResult = await AccountRepository.searchUser(' ')
    if (!apiResult.success) return;
    if (!apiResult.data) return;
    accounts.value = apiResult.data;
})
</script>

<style scoped></style>