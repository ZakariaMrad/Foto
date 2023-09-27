import { createApp } from "vue";
import "./styles.css";
import App from "./App.vue";
import router from "./router";
import { loadFonts } from './plugins/webfontloader'

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

app.use(router);
app.use(vuetify);

app.mount("#app");
