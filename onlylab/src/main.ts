import './assets/bootstrap.scss'
import './assets/app.scss'
import './assets/tracker.scss'

import { createApp } from 'vue'
import { createBootstrap } from 'bootstrap-vue-next'
import { createPinia} from "pinia";
import { VueQueryPlugin } from '@tanstack/vue-query'
import App from './App.vue'
import { errorHandler } from '@/services/error/handler'

const app = createApp(App) //.mount('#app')
app.use(createBootstrap())
app.use(createPinia())
app.use(VueQueryPlugin)
app.config.errorHandler = errorHandler

window.app = app

const ifLocalhost = window.location.host === 'localhost' // TODO: del
if (ifLocalhost) {
  app.mount('#app')
}
