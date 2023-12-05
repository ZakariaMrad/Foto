// Styles
import '@mdi/font/css/materialdesignicons.css'
import 'vuetify/styles'

// Vuetify
import { createVuetify } from 'vuetify'


const vuetify = createVuetify({
  ssr: true,
  theme: {
    defaultTheme: 'dark'
  }
})

export default vuetify
