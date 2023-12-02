<template>
    <v-dialog width="30%" v-model="props.activate">
        <v-card>
            <v-card-actions>
                <v-container>
                    <v-row>
                        <v-avatar style="margin-top: 10px; margin-right: 10px; margin-left: 10px;" >
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

const props = defineProps({
    activate: Boolean
});

</script>

<style scoped>

</style>