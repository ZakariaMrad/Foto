import { createApp } from "vue";
import { createVfm } from 'vue-final-modal';
import "./styles.css";
import 'vue-final-modal/style.css'
import App from "./App.vue";
import router from "./router";
// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const vuetify = createVuetify({
  components,
  directives,
})

const app = createApp(App);
const vfm = createVfm();

app.use(router);
app.use(vfm);
app.use(vuetify);


app.mount("#app");