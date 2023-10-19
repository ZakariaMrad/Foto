<template>
    <v-dialog width="90%" v-model="props.activate">
        <v-card>
            <v-card-text>
                <h3 class="text-center" md="12">Admin</h3>
                <v-responsive :aspect-ratio="3 / 1">
                    <v-card height="99%">
                        <v-tabs v-model="tab"  color="deep-purple-accent-4" fixed-tabs>
                            <v-tab value="0"  prepend-icon="mdi-information-outline">Plaintes</v-tab>
                            <v-tab value="1" 
                                prepend-icon="mdi-face">Blocage d'utilisateurs</v-tab>
                        </v-tabs>
                        <v-card-text>
                            <v-window v-model="tab">
                                <v-window-item value="0">
                                    <complaints/>
                                </v-window-item>

                                <v-window-item value="1">
                                    
                                </v-window-item>
                            </v-window>
                        </v-card-text>
                    </v-card>
                </v-responsive>
            </v-card-text>
            <v-card-action>
                <v-btn class="mb-5 ms-5" color="red-darken-3" @click="closeDialog()">Quitter</v-btn>
            </v-card-action>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import complaints from '../AdminComponents/Complaints.vue';

const emit = defineEmits(['closeDialog'])
const props = defineProps({
    activate: Boolean
})
const tab = ref<number>(0);

function closeDialog() {
    tab.value = 0;
    emit('closeDialog');
}

</script>

<style scoped></style>