<template>
    <v-app-bar>
        <v-toolbar app>
            <v-toolbar-title class="text-uppercase grey--text d-flex flex-wrap" height="auto">
                <span class="font-weight-bold ma-2 pa-2">Foto</span>
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <div class="text-center ma-2 pa-2">
                <v-menu open-on-hover>
                    <template v-slot:activator="{ props }">
                        <v-btn color="primary" icon="mdi-menu" v-bind="props">
                        </v-btn>
                    </template>

                    <v-list>
                        <v-list-item v-for="link in links" :prepend-icon="link.icon" :key="link.text" @click="link.click">
                            <v-list-item-title>{{ link.text }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </div>
        </v-toolbar>
        
    </v-app-bar>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { Events, EventsBus } from '../core/EventBus';
import Account  from '../models/Account';
import { useTheme } from 'vuetify'
const { bus } = EventsBus();

watch(() => bus.value.get(Events.CONNECTED_ACCOUNT), (value: Account[] | undefined) => {
    if (!value) return;
})

const links = ref<{ icon: string, text: string, click: any}[]>(
    [
        { icon: 'mdi-theme-light-dark', text: 'Mode sombre', click: toggleTheme },
        { icon: 'mdi-cog-outline', text: 'Paramètres', click: undefined},
        { icon: 'mdi-card-account-phone-outline', text: 'Nous contacter', click: undefined},
    ]
)


const theme = useTheme()

function toggleTheme () {
  theme.global.name.value = theme.global.current.value.dark ? 'light' : 'dark'
}



</script>