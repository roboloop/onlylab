import { fileURLToPath, URL } from 'node:url'
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { tampermonkey, type TampermonkeyOptions } from './plugins/tampermonkey'
import { notifier } from './plugins/notifier'
import Icons from 'unplugin-icons/vite'
import postcssPrefixSelector from 'postcss-prefix-selector'

const env = loadEnv(process.env.NODE_ENV!, process.cwd(), '')
const templatePath = env.NODE_ENV === 'production' ? 'tampermonkey/prod.ejs' : 'tampermonkey/dev.ejs'
const iconPath = env.NODE_ENV === 'production' ? 'tampermonkey/prod_icon.svg' : 'tampermonkey/dev_icon.svg'

export default defineConfig({
  plugins: [
    vue(),
    Icons({
      compiler: 'vue3',
    }),
    tampermonkey({
      templatePath,
      iconPath,
      data: {
        trackerUrl: env.VITE_TRACKER_URL,
        backendUrl: env.VITE_BACKEND_URL,
        appName: env.VITE_APP_NAME,
      }
    } as TampermonkeyOptions),
    ...(env.NODE_ENV === 'test' ? [] : [notifier(env.VITE_APP_NAME)]),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  css: {
    postcss: {
      plugins: [
        postcssPrefixSelector({
          // Same logic from src/services/dom/place.ts
          prefix: `.${env.VITE_APP_NAME.toLowerCase()}`,
          ignoreFiles: ['assets/tracker.scss'],
        }),
      ]
    }
  },
  build: {
    minify: false,
    // minify: 'terser',
    // terserOptions: {
    //   format: {
    //     comments: false,
    //   },
    // },
    rollupOptions: {
      output: {
        format: 'iife',
        entryFileNames: `[name].js`,
        chunkFileNames: `[name].js`,
        assetFileNames: `[name].[ext]`
      }
    },
  }
})
