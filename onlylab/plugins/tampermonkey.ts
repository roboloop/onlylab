import type { Plugin } from 'vite'
import {renderFile} from 'ejs'
import type {OutputAsset, OutputBundle, OutputChunk} from "rollup";

export interface TampermonkeyOptions {
  templatePath: string
  data: {
    backendUrl: string
  }
}

export function tampermonkey(options: TampermonkeyOptions): Plugin {
  return {
    name: 'tampermonkey',
    buildStart() {
      this.addWatchFile(options.templatePath)
    },
    async generateBundle(outputOptions, bundle: OutputBundle): Promise<void> {
      const js = (bundle['assets/index.js'] as OutputChunk).code ?? ''
      const css = (bundle['assets/index.css'] as OutputAsset)?.source ?? ''

      const result = await renderFile(options.templatePath, {
        js,
        css,
        ...options.data,
      }, {async: true})

      this.emitFile({
        type: 'asset',
        fileName: 'tampermonkey.js',
        source: result
      })
    },
  }
}
