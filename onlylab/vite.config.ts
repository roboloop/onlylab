import { fileURLToPath, URL } from 'node:url'
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import { tampermonkey, type TampermonkeyOptions } from './plugins/tampermonkey'
import { notifier } from './plugins/notifier'
import packageJson from './package.json' with {type: 'json'}
import Icons from 'unplugin-icons/vite'
import postcssPrefixSelector from 'postcss-prefix-selector'

const env = loadEnv('', process.cwd(), '')
const templatePath = env.NODE_ENV === 'production' ? 'tampermonkey/prod.ejs' : 'tampermonkey/dev.ejs'

export default defineConfig({
  plugins: [
    vue(),
    Icons({
      compiler: 'vue3',
      // scale: 0.75,
    }),
    tampermonkey({
      templatePath,
      data: {
        backendUrl: env.VITE_BACKEND_URL,
      }
    } as TampermonkeyOptions),
    ...(env.NODE_ENV === 'test' ? [] : [notifier(packageJson.name)]),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        api: 'modern-compiler'
      },
    },
    postcss: {
      plugins: [
        postcssPrefixSelector({
          prefix: '#app',
          // includeFiles: ['assets/bootstrap.scss'],
          ignoreFiles: ['assets/tracker.scss'],
          // transform(prefix, selector, prefixedSelector, file) {
          //   console.log('===' + file)
          //   return prefixedSelector
          // },
        }),
      ]
    }
  },
  build: {
    rollupOptions: {
      output: {
        format: 'iife',
        entryFileNames: `assets/[name].js`,
        chunkFileNames: `assets/[name].js`,
        assetFileNames: `assets/[name].[ext]`
      }
    },
  }
})
