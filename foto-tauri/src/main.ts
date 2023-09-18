import { createApp } from "vue";
import "./styles.css";
import App from "./App.vue";
import router from './router'


createApp(App).mount("#app");

App.use(router)

App.mount('#app')
//   .$nextTick(() => postMessage({ payload: 'removeLoading' }, '*'))