<template>
    <v-card>
        <v-list style="display: flex; flex-wrap: wrap; flex-direction:row;">
            <v-list-item subtitle="Owner" :title="account?.name" />
            <v-list-item v-for="collaboraters in props.album.collaborators" subtitle="Collaborater"
                :title="collaboraters.name" />
            <v-list-item v-for="spectator in props.album.spectators" subtitle="Spectator" :title="spectator.name" />
            <v-list-item />
        </v-list>
        <v-container fluid>
            <v-row dense>
                <v-col>
                    <v-card>
                        <v-carousel hide-delimiters height="100%" v-if="props.album.type === 'carousel'">
                            <v-carousel-item v-for="(foto, i) in props.album.fotos" :key="i - 1">
                                <v-img :src="foto.path" aspect-ratio="3"
                                :style="{filter: 'saturate(' + foto.saturation +'%) contrast(' + foto.contrast +'%) brightness(' + foto.exposition +'%)'}"
                            ></v-img>
                            </v-carousel-item>
                        </v-carousel>
                        <v-table v-else>
                            <tbody>
                                <tr v-for="(_, y) in album.grid?.nbRows">
                                    <td v-for="(_, x) in album.grid?.nbCols" class="pa-1">
                                        <v-responsive aspect-ratio="1"
                                            v-if="(y * album.grid!.nbCols + x) < album.grid?.fotosPosition.length!">
                                            <v-card height="100%">
                                                <v-img :src="getPath(y * album.grid!.nbCols + x)" aspect-ratio="1" 
                                                :style="{filter: 'saturate(' + getFoto(y * album.grid!.nbCols! + x)?.saturation +'%) contrast(' + getFoto(y * album.grid!.nbCols! + x)?.contrast +'%) brightness(' + getFoto(y * album.grid!.nbCols! + x)?.exposition +'%)'}"
                            />
                                            </v-card>
                                        </v-responsive>
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-card>
</template>

<script setup lang="ts">
import Account from '../../models/Account';
import Album from '../../models/Album';


const props = defineProps<{
    account: Account | undefined,
    album: Partial<Album>
}>()

const getPath = (index: number) => {
    const id = props.album.grid!.fotosPosition[index];
    return props.album.fotos!.find(foto => foto.idFoto === id)?.path;
}

const getFoto = (index: number) => {
    const id = props.album.grid!.fotosPosition[index];
    return props.album.fotos!.find(foto => foto.idFoto === id);
}
</script>

<style scoped>
.horizontal-list-item {
    display: inline-block;
    flex-wrap: wrap;
}
</style>