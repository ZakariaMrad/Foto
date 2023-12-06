<template>
    <v-responsive aspect-ratio="3">
        <v-card height="100%" class="card-outter">
            <form @submit.prevent="submitFn">

                <v-card-text>
                    <SelectAccountSpectateCollab :isPublic="props.album.isPublic!" :disabled="props.disabled"
                        @collaboraters="(collaboraters) => addCollaboraters(collaboraters)"
                        @spectators="(spectators) => addSpectators(spectators)" />
                </v-card-text>
                <v-card-actions align-end class="card-actions">
                    <v-btn class="btn btn-danger" @click="closeDialog()" color="red-darken-3">Annuler</v-btn>
                    <v-btn class="btn" color="indigo" @click="back()">Précédent</v-btn>
                    <v-btn type="submit" :success="success" :loading="loading" class="text-white" color="green-darken-3"
                        text="Valider" />
                </v-card-actions>
            </form>
        </v-card>
    </v-responsive>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Album from '../../models/Album';
import { useFormHandler } from 'vue-form-handler'
import Account from '../../models/Account';
import SelectAccountSpectateCollab from './SelectAccountSpectateCollab.vue';

const props = defineProps<{ album: Partial<Album>, disabled: boolean }>()

const { register, handleSubmit, formState, unregister } = useFormHandler({
    validationMode: 'always',
})
const successFn = async (partialAlbum: Partial<Album>) => {
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
const collaboraters = ref<Account[]>([]);
const spectators = ref<Account[]>([]);


const emit = defineEmits<{ (event: 'nextStep', album: Partial<Album>): void, (event: 'closeDialog'): void, (event: 'back'): void }>()

onMounted(async () => {
    register('collaboraters', { defaultValue: [] })
    register('spectators', { defaultValue: [] })
    console.log('ispublic',props.album);
    
})

function addCollaboraters(colls: Account[]) {
    collaboraters.value = colls;
    unregister('collaboraters');
    register('collaboraters', { defaultValue: collaboraters })
}
function addSpectators(spects: Account[]) {
    spectators.value = spects;
    unregister('spectators');
    register('spectators', { defaultValue: spectators })
}


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