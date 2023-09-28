<template>
    <v-main>
        <v-navigation-drawer expand-on-hover rail>
            <v-list>
                <v-list-item prepend-avatar="https://randomuser.me/api/portraits/women/85.jpg" title="Sandra Adams"
                    subtitle="sandra_a88@gmail.com"></v-list-item>
            </v-list>

            <v-divider></v-divider>

            <v-list density="compact" nav>
                <v-list-item prepend-icon="mdi-home" title="Acceuil" :to="{ name: 'home' }"></v-list-item>
                <v-list-item prepend-icon="mdi-star" title="Populaire"></v-list-item>
                <v-list-item prepend-icon="mdi-account" title="Mon profil" :to="{ name: 'profil' }"></v-list-item>
                <v-list-item prepend-icon="mdi-upload" title="Téléverser" :to="{ name: 'upload' }"></v-list-item>
            </v-list>
        </v-navigation-drawer>
        <div class="mx-16 my-1">
            <slot></slot>
        </div>


    </v-main>
</template>