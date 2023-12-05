<template>
    <v-dialog width="30%" v-model="props.activate">
        <form @submit.prevent="submitFn">
        <v-card>
            <v-card-text>
                <v-card-title class="text-center">Bloquer {{ account?.name }} - {{ account?.email }}</v-card-title>
                    <v-col>
                        <v-text-field :label="`Raison du blockage de  ${account!.name}`" required
                            v-bind="register('reason')" :loading="loading" />
                        <p class="text-danger">{{ errorMessage }} </p>
                        <p class="text-success">{{ successMessage }} </p>
                    </v-col>
                </v-card-text>
                <v-card-actions>
                    <v-btn class="mb-5 ms-5" color="red-darken-3" @click="closeDialog()">Annuler</v-btn>
                    <v-btn class="mb-5 ms-5" color="green-darken-3" @click="submitFn" :loading="loading">Bloquer</v-btn>
                </v-card-actions>
            </v-card>
        </form>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import Account from '../../models/Account';
import { useFormHandler } from 'vue-form-handler';
import UserBlockRepository from '../../repositories/UserBlockRepository';
//@ts-ignore
import delay from 'delay';


const errorMessage = ref<string|undefined>(undefined);
const successMessage = ref<string|undefined>(undefined);
const loading = ref<boolean>(false);

const { register, handleSubmit, formState } = useFormHandler({
    validationMode: 'always',
})
const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean,
    account: Account
})

function closeDialog() {
    emit('closeDialog');
}
const successFn = async (form: any) => {
    errorMessage.value = undefined;
    successMessage.value = undefined;
    console.log(form, form.reason);


    let apiResult = await UserBlockRepository.blockUser(form.reason, props.account!.idAccount);
    if (!apiResult.success) {
        errorMessage.value = apiResult.errors![0].message;
        return
    }
    if (!apiResult.data) return
    successMessage.value = apiResult.data.message;
    await delay(2000);
    loading.value = false;
    emit('closeDialog');

}

const submitFn = () => {
    try {
        handleSubmit(successFn)
    } catch {
        //do anything with errors
        console.log(formState.errors)
    }
}
</script>

<style scoped></style>