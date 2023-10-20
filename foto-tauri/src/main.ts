import { createApp } from "vue";
import "./styles.css";
import App from "./App.vue";
import router from "./router";
import { loadFonts } from './plugins/webfontloader'

//draggable items
import vueTauriDraggable from 'vue-tauri-draggable';

//Event emitter
import mitt from 'mitt';

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

import '@mdi/font/css/materialdesignicons.css'
import { aliases, mdi } from "vuetify/iconsets/mdi";


const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
    aliases,
    sets: {
      mdi,
    },
  }
})


const app = createApp(App);

loadFonts()

const emitter = mitt();
app.config.globalProperties.emitter = emitter;
app.use(router);

app.use(vuetify);

app.use(vueTauriDraggable);

app.mount("#app");
