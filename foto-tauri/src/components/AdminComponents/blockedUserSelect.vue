<template>
    <tr>
        <td>{{ props.blockedUser.account.name }}<br/> {{ props.blockedUser.account.email }}</td>
        <td>{{ props.blockedUser.reason }}</td>
        <td style="text-align: center;">
            <v-btn color="red-darken-3"
                icon="mdi-minus" variant="plain" @click="selectUser()" />
        </td>
    </tr>
</template>

<script setup lang="ts">
import UserBlock from '../../models/UserBlock';
const props = defineProps<{ blockedUser: UserBlock}>()

const emit = defineEmits<{
    (event: 'selectUser', user: UserBlock): void
}>()

function selectUser() {
    emit('selectUser', props.blockedUser)
}

</script>

<style scoped></style>