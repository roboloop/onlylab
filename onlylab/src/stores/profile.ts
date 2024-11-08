import { defineStore } from 'pinia'
import {ref, computed} from 'vue'
import {useQuery} from "@tanstack/vue-query";
import {NormalizedProfile, normalizeProfiles} from "@/services/babepedia/actress";



export const useProfileStore = defineStore('profile', () => {
  const profiles = ref<NormalizedProfile[]>([])

  const { data: normalizedProfiles, refetch } = useQuery({
    queryKey: [text],
    queryFn: async () => normalizeProfiles(names, forced),
    refetchOnWindowFocus: false,
    refetchOnReconnect: false,
    refetchOnMount: false,
  })

  return { count, name, doubleCount, increment }
})
