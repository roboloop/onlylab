import { defineStore } from 'pinia'
import { ref, shallowRef } from 'vue'

import type { File } from '@/services/clients/tracker'
import type { ImageLink } from '@/services/dom/topic'

type ViewMode = 'images' | 'pack' | 'files'

export const useViewModeStore = defineStore('view-mode', () => {
  const viewMode = shallowRef<ViewMode>('images')
  const imageLinks = ref<ImageLink[] | string[]>([])
  const pack = ref<ImageLink[]>([])
  const files = ref<File[]>([])

  function selectImageMode(newImageLinks: ImageLink[]): void {
    viewMode.value = 'images'
    imageLinks.value.splice(0)
    imageLinks.value = newImageLinks
  }

  function selectPackMode(imageLinks: ImageLink[]): void {
    viewMode.value = 'pack'
    pack.value.splice(0)
    pack.value = imageLinks
  }

  function selectFileMode(newFiles: File[]): void {
    viewMode.value = 'files'
    files.value = newFiles
  }

  return {
    viewMode,
    imageLinks,
    pack,
    files,

    selectImageMode,
    selectPackMode,
    selectFileMode,
  }
})
