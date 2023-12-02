<template>
    <v-dialog width="30%" v-model="props.activate">
        <v-card>
            <v-btn style="position: absolute; top:5px; right: 5px; z-index: 10;" class="toolbar-btn" variant="tonal" color="black" icon="$close" @click="closeDialog()"></v-btn>
            <v-list>
                <v-list-item v-for="item in 10" prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg">
                    <p>LOREM OASINDIOUASBNFIUBAPISUBFPAISUFJAHSFOIAHSFJASJKFHALJKSFHLASJHFLUIASHFUAHSFHNAS:OFHALSKJFNLASKJNF</p>
                    <v-divider></v-divider>
                </v-list-item>
            </v-list>
            <v-card-actions>
                <v-container>
                    <v-row>
                        <v-avatar style="margin-top: 10px; margin-right: 10px; margin-left: 10px;">
                            <v-img src="https://randomuser.me/api/portraits/women/85.jpg"></v-img>
                        </v-avatar>
                        <v-text-field label="Laisser un commentaire..."></v-text-field>
                        <v-btn icon="mdi-send" style="margin-top: 5px; transform: rotate(-25deg);"></v-btn>
                    </v-row>
                </v-container>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup lang="ts">

import { onMounted } from 'vue';

onMounted(() => {
});

const emit = defineEmits(['closeDialog'])

const props = defineProps({
    activate: Boolean
});

function closeDialog()
{
    emit('closeDialog');
}

</script>

<style scoped>

</style>