import { createApp } from 'vue/dist/vue.esm-browser.js'

import Header from '../components/Header.vue'
import Main from '../components/Main.vue'
import Footer from '../components/Footer.vue'

const app = createApp({})

// Enregistrement global
app.component('HeaderApp', Header)
app.component('MainApp', Main)
app.component('FooterApp', Footer)

app.mount('#app')
