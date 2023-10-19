<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">
                {{ album }}
                <v-card-text>
                    <FinalizationAlbum :account="account" :album="album" />
                </v-card-text>
                <v-card-actions align-end class="card-actions">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn class="btn" color="indigo" @click="back()">Précédent</v-btn>
                    <v-btn type="submit" :success="success" :loading="loading" class="text-white" color="green-darken-3"
                        text="Terminer" />
                </v-card-actions>
            </form>
        </v-card>
    </v-responsive>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Album from '../../models/Album';
import { useFormHandler } from 'vue-form-handler'
import FinalizationAlbum from './FinalizationAlbum.vue';
import AccountRepository from '../../repositories/AccountRepository';
import Account from '../../models/Account';

const props = defineProps<{ album: Partial<Album> }>()

const account = ref<Account>();

const { handleSubmit, formState, } = useFormHandler({
    validationMode: 'always',
})

onMounted(async () => {
    let apiResult = await AccountRepository.getAccount();
    if (!apiResult.success) return
    account.value = apiResult.data;
})
const successFn = async (partialAlbum: any) => {
    loading.value = true;
    const album: Partial<Album> = { ...props.album, ...partialAlbum };
    emit('nextStep', album);
    loading.value = false;
}

const submitFn = () => {
    try {
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}

const loading = ref(false);
const success = ref(false);


const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void, (event: 'back'): void }>()

function closeDialog() {
    emit('closeDialog');
}
function back() {
    emit('back');
}

</script>

<style scoped>
.card-outter {
    padding-bottom: 50px;
}

.card-actions {
    position: absolute;
    bottom: 0;
    right: 0;
}
</style>