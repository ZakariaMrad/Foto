<template>
    <v-app>
        <NavBar />
        <SideBar>
            <Acceuil />
            <slot></slot>
        </SideBar>
    </v-app>
</template>

<script setup lang="ts">
import SideBar from "../../components/SideBar.vue";
import NavBar from "../../components/NavBar.vue";
import Acceuil from "../../components/Acceuil.vue";
</script>

<style scoped></style>
