import { defineStore } from 'pinia'
import { computed, ref, toValue } from 'vue'
import { normalizeProfiles } from '@/services/babepedia/actress'
import { removeProfile } from '@/services/store/profiles'
import { useQuery } from '@tanstack/vue-query'

export const useProfileStore = defineStore('profile', () => {
  const names = ref<string[][]>([])
  const { data: normalizedProfiles, refetch } = useQuery({
    queryKey: [names],
    queryFn: async () => await normalizeProfiles(names.value),
    initialData: [],
    enabled: () => names.value.length > 0,
  })
  const mainNames = computed(() =>
    normalizedProfiles.value.map((n): [string, string | undefined] => [n.mainName, n.babeName]),
  )

  function loadProfiles(newNames: string[][]): void {
    names.value = newNames
  }

  // TODO: is it a good place for that code?
  async function cleanCache(): Promise<void> {
    for (const name of toValue(names).flatMap(n => n)) {
      await removeProfile(name)
    }
    refetch()
  }

  return {
    normalizedProfiles,
    loadProfiles,
    mainNames,
    cleanCache,
  }
})
