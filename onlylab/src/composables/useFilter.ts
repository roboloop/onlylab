import { computed, ref } from 'vue'
import { Colorizer } from '@/services/utils/colorizer'
import { extractFilteredWords } from '@/services/utils/filter'

import type { Ref } from 'vue'

export function useFilter<T extends object>(
  items: Ref<T[]>,
  filterField: keyof { [K in keyof T as T[K] extends string ? K : never]: T[K] },
  totalFields: Array<keyof { [K in keyof T as T[K] extends number | string | undefined ? K : never]: T[K] }>,
) {
  const filter = ref<string>('')

  const filteredItems = computed(() =>
    items.value.reduce((acc, item) => {
      if (typeof item[filterField] !== 'string') {
        return acc
      }

      const filteredWords = extractFilteredWords(item[filterField].toLowerCase(), filter.value)
      if (!filter.value || filteredWords.length > 0) {
        const html = new Colorizer(item[filterField]).addWords(filteredWords, false, 'filter').build()
        acc.push({ ...item, name: html })
      }

      return acc
    }, [] as T[]),
  )

  const totalComputed = totalFields.map(f =>
    computed(() =>
      filteredItems.value.reduce((acc, item) => {
        if (typeof item[f] !== 'number') {
          return acc
        }

        return acc + item[f]
      }, 0),
    ),
  )

  return {
    filter,
    filteredItems,
    totalComputed,
  }
}
