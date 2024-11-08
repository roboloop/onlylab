import { Plugin } from 'vite';
import nodeNotifier from 'node-notifier'

export function notifier(name: string): Plugin {
  return {
    name: 'notifier',
    buildEnd(error) {
      const successMessage = 'Build success'
      const errorMessage = 'Build error' + (error && 'id' in error ? `: ${error.id}` : '')

      nodeNotifier.notify({
        title: name,
        message: error ? errorMessage : successMessage,
      });
    },
  }
}
