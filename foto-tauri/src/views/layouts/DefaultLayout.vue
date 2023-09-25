<template>
    <v-app>
        <NavBar />
        <SideBar>
            <slot></slot>
        </SideBar>
    </v-app>
</template>

<script setup lang="ts">
import SideBar from "../../components/SideBar.vue";
import NavBar from "../../components/NavBar.vue";
</script>

<style scoped></style>