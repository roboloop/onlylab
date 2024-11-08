import { onBeforeUnmount, ref, watchEffect } from 'vue'

import type { Ref } from 'vue'

export function useReTitle(title: string, array: Ref<unknown[]>) {
  function reset(): void {
    loaded.value = 0
    document.title = title
  }

  const loaded = ref<number>(0)
  function inc(): void {
    loaded.value++
  }

  watchEffect(() => {
    document.title = `[${loaded.value}/${array.value.length}] ${title}`
  })

  onBeforeUnmount(() => {
    document.title = title
  })

  return {
    reset,
    inc,
  }
}
