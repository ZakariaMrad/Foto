<template>
    <tr>
        <td>{{ props.account.name }}</td>
        <td>{{ props.account.email }}</td>
        <td style="text-align: center;">
            <v-btn v-if="props.isPublic || props.disabled" color="red-darken-3" icon="mdi-minus"
                variant="plain"  :disabled="true" />
            <v-btn v-else :color="isSpectator ? 'red-darken-3' : 'green-darken-3'" :icon="isSpectator ? 'mdi-minus' : 'mdi-plus'"
                variant="plain" @click="toggleSpectator()" :disabled="isCollaborater" />
        </td>
        <td style="text-align: center;">
            <v-btn :color="isCollaborater ? 'red-darken-3' : 'green-darken-3'"
                :icon="isCollaborater ? 'mdi-minus' : 'mdi-plus'" variant="plain" @click="toggleCollaborater()" :disabled="props.disabled" />
        </td>
    </tr>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Account from '../../models/Account';
const props = defineProps<{ account: Account, isCollaborater: boolean, isSpectator: boolean, isPublic: boolean, disabled:boolean }>()

const isSpectator = ref(false)
const isCollaborater = ref(false)

onMounted(() => {
    isSpectator.value = props.isSpectator
    isCollaborater.value = props.isCollaborater
})

const emit = defineEmits<{
    (event: 'addCollaborater', user: Account): void
    (event: 'removeCollaborater', user: Account): void
    (event: 'addSpectator', user: Account): void
    (event: 'removeSpectator', user: Account): void
}>()

function toggleCollaborater() {
    isCollaborater.value = !isCollaborater.value
    if (isCollaborater.value) {
        emit('addCollaborater', props.account)
    } else {
        emit('removeCollaborater', props.account)
    }
    isSpectator.value = isCollaborater.value;
}
function toggleSpectator() {
    isSpectator.value = !isSpectator.value
    if (isSpectator.value) {
        emit('addSpectator', props.account)
    } else {
        emit('removeSpectator', props.account)
    }
}

</script>

<style scoped></style>