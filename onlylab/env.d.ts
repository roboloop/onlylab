/// <reference types="unplugin-icons/types/vue" />
/// <reference types="vite/client" />

import type {App} from 'vue'

declare global {
  interface Window {
    app: App
  }
}
