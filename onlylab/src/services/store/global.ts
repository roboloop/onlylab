import { ref } from 'vue'

import type { File } from '@/services/clients/tracker'
import type { ImageLink } from '@/services/dom/topic'

type ViewMode = 'images' | 'pack' | 'files' | 'babepedia'

export const viewMode = ref<ViewMode>('images')
export const imageLinks = ref<ImageLink[] | string[]>([])
export const pack = ref<ImageLink[]>([])
export const files = ref<File[]>([])
