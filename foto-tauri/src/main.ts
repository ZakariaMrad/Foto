import { createApp } from 'vue'
import App from './App.vue'
import { loadFonts } from './plugins/webfontloader'


import router from './router'

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Material design
import '@mdi/font/css/materialdesignicons.css'


const vuetify = createVuetify({
  components,
  directives,
  icons: {
    defaultSet: 'mdi',
  }
})

loadFonts()

const app = createApp(App)

app.use(router)

app.use(vuetify).mount('#app')


