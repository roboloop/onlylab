import {ref, computed, onMounted} from 'vue'
import type {NormalizedProfile} from "@/services/babepedia/actress";
import { useQuery } from '@tanstack/vue-query'
import {normalizeProfiles} from "@/services/babepedia/actress";
import {parseTitle} from "@/services/parsers/title";
import {parseNames} from "@/services/parsers/name";

export function useProfiles(text: string) {
  const title = parseTitle(text)
  const names = parseNames(title)

  let forced = false
  const { data: normalizedProfiles, refetch } = useQuery({
    queryKey: [text],
    queryFn: async () => normalizeProfiles(names, forced),
    refetchOnWindowFocus: false,
    refetchOnReconnect: false,
    refetchOnMount: false,
  })

  const mainNames = computed(() => normalizedProfiles?.value?.map(n => n.mainName) ?? [])

  async function reloadProfiles(): Promise<void> {
    forced = true
    await refetch()
    forced = false
  }

  return {
    mainNames,
    normalizedProfiles,
    reloadProfiles,
  }
}
