import { defineStore } from 'pinia'
import { computed, ref, toValue } from 'vue'
import { normalizeImages } from '@/services/parsers/image'
import { removeImage } from '@/services/store/images'

import type { ImageNode } from '@/services/dom/topic'

export const useImageStore = defineStore('image', () => {
  const imageNodes = ref<ImageNode[]>([])
  const topicImages = computed(() => {
    if (imageNodes.value.length === 0) {
      return []
    }

    const topic = imageNodes.value[0]!
    return normalizeImages(topic)
  })
  const commentImages = computed(() => {
    const [, ...comments] = imageNodes.value
    return normalizeImages(comments)
  })
  const topicPackImages = computed(() => topicImages.value.filter(n => n.imageLinks.some(i => i.header)))
  const commentPackImages = computed(() => topicImages.value.filter(n => n.imageLinks.some(i => i.header)))

  function loadImages(i: ImageNode[]): void {
    imageNodes.value = i
  }

  async function cleanCache(): Promise<void> {
    const collectTitles = (imageNodes: ImageNode[]): string[] => {
      return [
        ...imageNodes.flatMap(n => n.images.map(i => i.title)),
        ...imageNodes.flatMap(n => collectTitles(n.children)),
      ]
    }

    for (const title of collectTitles(toValue(imageNodes))) {
      await removeImage(title)
    }
  }

  return {
    topicImages,
    commentImages,
    topicPackImages,
    commentPackImages,
    loadImages,
    cleanCache,
  }
})
