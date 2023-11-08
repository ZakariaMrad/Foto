<template>
    <v-card variant="outlined" class="pa-2">

        <v-row>
            <v-col cols="2">
                {{ props.account?.idAccount }}
            </v-col>
            <v-col cols="5">
                {{ props.account?.name }}
            </v-col>
            <v-col cols="5">
                {{ props.account?.email }}
            </v-col>
        </v-row>
    </v-card>
</template>

<script setup lang="ts">
import Account from '../../models/Account';

const props = defineProps({
    account: Account
})

</script>

<style scoped></style>