import './assets/bootstrap.scss'
import './assets/app.scss'
import './assets/tracker.scss'

import { createBootstrap } from 'bootstrap-vue-next'
import { createPinia } from 'pinia'
import { createApp } from 'vue'
import App from './App.vue'
import { addBasePlace, baseId } from '@/services/dom/injector'
import { errorHandler } from '@/services/error/handler'
import { QueryClient, VueQueryPlugin } from '@tanstack/vue-query'

const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      refetchOnWindowFocus: false,
      refetchOnReconnect: false,
      refetchOnMount: false,
    },
  },
})

const app = createApp(App)
app.use(createBootstrap())
app.use(createPinia())
app.use(VueQueryPlugin, { queryClient })
app.config.errorHandler = errorHandler

addBasePlace(document)
app.mount(baseId)
