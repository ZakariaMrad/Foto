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
import 'material-design-icons-iconfont/dist/material-design-icons.css'


const vuetify = createVuetify({
  components,
  directives,
})

loadFonts()

const app = createApp(App)

app.use(router)

app.use(vuetify).mount('#app')


