/* eslint-disable */
import { readFileSync } from 'fs'
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import notifier from 'node-notifier'
import packageJson from './package.json'
import path from 'path'

const env = loadEnv('', process.cwd(), 'VITE_')

const tampermonkey = (templateFile) => {
  return {
    name: 'tampermonkey',
    buildStart() {
      this.addWatchFile(templateFile)
    },
    generateBundle(outputOptions, bundle) {
      const template = readFileSync(templateFile, 'utf8')
      const js = bundle['assets/index.js'].code
      const css = bundle['assets/index.css'].source

      const backendUrl = env.VITE_DEV_BACKEND
      let result = template.replaceAll(/<%= backend_url %>/g, backendUrl)

      if (result.includes('<%= css %>')) {
        const [first, second, third] = result.split(/(?:<%= css %>)|(?:<%= js %>)/)
        result = first + css + second + js + third
      }

      this.emitFile({
        type: 'asset',
        fileName: 'tampermonkey.js',
        source: result
      })
    },
  }
}

const notifierPlugin = () => {
  return {
    name: 'tampermonkey',
    buildEnd(error) {
      const message = error ? `Build error: ${error.id}` : 'Build success'

      notifier.notify({
        title: packageJson.name,
        message,
      });
    },
  }
}

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue({
      template: {
        compilerOptions: {
          compatConfig: {
            MODE: 2
          }
        }
      }
    }),
    tampermonkey(process.env.NODE_ENV === 'production' ? 'tampermonkey/prod.js.template' : 'tampermonkey/dev.js.template'),
    notifierPlugin(),
  ],
  resolve: {
    alias: {
      vue: '@vue/compat',
      '@': path.resolve(__dirname, './src'),
    }
  },
  build: {
    rollupOptions: {
      output: {
        entryFileNames: `assets/[name].js`,
        chunkFileNames: `assets/[name].js`,
        assetFileNames: `assets/[name].[ext]`
      }
    },
  }
})
