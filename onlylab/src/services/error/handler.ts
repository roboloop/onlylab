import type { ComponentPublicInstance } from 'vue'

export function errorHandler(err: unknown, instance: ComponentPublicInstance | null, info: string) {
  console.error('Error:', err, instance, info)
}
