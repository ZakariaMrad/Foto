<template>
    <v-main>
        <v-navigation-drawer expand-on-hover rail>
            <v-list>
                <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" title="Sandra Adams"
                    subtitle="sandra_a88@gmail.com"></v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-home" title="Acceuil" :to="{ name: 'home' }" value="myfiles"></v-list-item>
                <v-dialog width="500">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" prepend-icon="mdi-magnify" title="Recherche"
                            value="starred"></v-list-item>
                    </template>

                    <template v-slot:default="{ isActive }">
                        <v-card title="Rechercher">
                            <v-text-field v-model="search" class="pa-5" append-icon="mdi-magnify" label="Search" single-line
                                hide-details></v-text-field>

                            <v-card-actions>
                                <v-spacer></v-spacer>

                                <v-btn text="Fermer" @click="isActive.value = false"></v-btn>
                            </v-card-actions>
                        </v-card>
                    </template>
                </v-dialog>
                <v-list-item prepend-icon="mdi-star" title="Populaire" value="shared"></v-list-item>
                <v-list-item prepend-icon="mdi-account" title="Mon profil" :to="{ name: 'profil' }"
                    value="starred"></v-list-item>
            </v-list>
        </v-navigation-drawer>
        <div class="mx-16 my-1">
            <slot></slot>
        </div>


    </v-main>
</template>