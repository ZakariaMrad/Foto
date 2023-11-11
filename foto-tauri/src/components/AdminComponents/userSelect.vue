<template>
    <tr>
        <td>{{ props.account.name }}</td>
        <td>{{ props.account.email }}</td>
        <td style="text-align: center;">
            <v-btn :color="isChoosen ? 'red-darken-3' : 'green-darken-3'"
                :icon="isChoosen ? 'mdi-minus' : 'mdi-plus'" variant="plain" @click="selectUser()" />
        </td>
    </tr>
</template>

<script setup lang="ts">
import Account from '../../models/Account';
const props = defineProps<{ account: Account, isChoosen:boolean }>()

const emit = defineEmits<{
    (event: 'selectUser', user: Account): void
}>()

function selectUser() {
    emit('selectUser', props.account)
}

</script>

<style scoped></style>