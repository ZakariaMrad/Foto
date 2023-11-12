<template>
    <div class="d-flex align-center flex-column mt-3">
        <h1 class="text-h2 text-bold">Ma liste d'amis(es)</h1>
        <h3 class="text-bold"> Vous avez 10 amis(es)</h3>
    </div>
    <v-card class="mx-auto ma-10" width="75%" height="100%">
        <v-card-text>
            <v-list lines="one">
                <v-list-item v-for="n in 10" :key="n">
                    <v-col cols="12">
                        <v-row>
                            <v-col cols="7">
                                <v-row>
                                    <v-avatar size="50">
                                        <v-img src="https://randomuser.me/api/portraits/women/85.jpg"
                                            alt="Sandra Adams"></v-img>
                                    </v-avatar>
                                    <v-list-item :title="'Profile name'" subtitle="exemple@exemple.com"></v-list-item>
                                </v-row>
                            </v-col>
                            <v-col cols="5">
                                <v-row>
                                    <v-list-item>120 publications</v-list-item>
                                    <v-list-item>752 Suiveurs</v-list-item>
                                    <v-list-item>1,5k Suivi</v-list-item>
                                    <v-list-item>
                                        <v-btn color="primary" icon="mdi-dots-horizontal">
                                        </v-btn>
                                    </v-list-item>

                                </v-row>
                            </v-col>

                        </v-row>
                        <hr class="mx-auto" width="75%">
                    </v-col>

                </v-list-item>
            </v-list>

        </v-card-text>

    </v-card>
</template>

<script setup lang="ts">
</script>