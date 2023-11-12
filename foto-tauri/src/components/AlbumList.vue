<template>
    <v-col class="d-flex justify-end">
    </v-col>
    <v-col>
        <v-row>
            <v-col v-for="album in albums" cols="6">
                <AlbumListItem :album="album" @deleted-album="reload"/>
            </v-col>
        </v-row>
    </v-col>
</template>

<script setup lang="ts">
import { onMounted, ref, watch } from 'vue';
import AlbumListItem from './AlbumListItem.vue';
import Album from '../models/Album';
import AlbumRepository from '../repositories/AlbumRepository';
import { EventsBus, Events } from '../core/EventBus';

const { bus } = EventsBus();

const albums = ref<Album[]>([])

onMounted(async () => {
    getAlbums();
})

function reload() {
    getAlbums();
}

watch(()=> bus.value.get(Events.RELOAD_ALBUMS), async () => {
    await getAlbums();
})


async function getAlbums() {
    let apiResponse = await AlbumRepository.getAlbums();
    if (!apiResponse.success) return;
    albums.value = apiResponse.data;
    console.log(' albums', albums.value);
    
}


</script>
