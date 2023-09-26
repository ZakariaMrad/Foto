import { createApp } from "vue";
import "./styles.css";
import App from "./App.vue";
import router from "./router";
import mitt from 'mitt';
// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import "@mdi/font/css/materialdesignicons.css";

const vuetify = createVuetify({
  components,
  directives,
  icons:{
    defaultSet: 'mdi'
  }
})


const app = createApp(App);
const emitter = mitt();
app.config.globalProperties.emitter = emitter;
app.use(router);
app.use(vuetify);

app.mount("#app");
