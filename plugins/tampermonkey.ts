import type { Plugin } from 'vite'
import {renderFile} from 'ejs'
import fs from 'node:fs/promises'

import type {OutputAsset, OutputBundle, OutputChunk} from 'rollup';

export interface TampermonkeyOptions {
  templatePath: string
  iconPath: string
  data: {
    backendUrl: string
    appName: string
  }
}

export function tampermonkey(options: TampermonkeyOptions): Plugin {
  return {
    name: 'tampermonkey',
    buildStart() {
      this.addWatchFile(options.templatePath)
    },
    async generateBundle(outputOptions, bundle: OutputBundle): Promise<void> {
      const js = (bundle['index.js'] as OutputChunk).code ?? ''
      const css = (bundle['index.css'] as OutputAsset)?.source ?? ''

      const icon = await fs.readFile(options.iconPath, {encoding: 'utf-8'})
      const base64Icon = 'data:image/svg+xml;base64,' + btoa(decodeURIComponent(encodeURIComponent(icon)))

      const filename = `${options.data.appName.toLowerCase()}.user.js`
      const result = await renderFile(options.templatePath, {
        js,
        css,
        base64Icon,
        filename,
        ...options.data,
      }, {async: true})

      this.emitFile({
        type: 'asset',
        fileName: filename,
        source: result
      })
    },
  }
}
