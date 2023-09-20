import { createApp } from "vue";
import "./styles.css";
import App from "./App.vue";
import router from './router'
// import { Vuetify } from 'vuetify'




const app = createApp(App);

// app.use(Vuetify);

app.use(router);

app.mount('#app')