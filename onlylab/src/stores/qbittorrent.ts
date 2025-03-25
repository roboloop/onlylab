import { defineStore } from 'pinia'
import { ref } from 'vue'
import { client } from '@/services/clients/client'
import { useQuery } from '@tanstack/vue-query'

import type { Placeholder } from '@/services/utils/strings'

export const useQbittorrentStore = defineStore('qbittorrent', () => {
  const downloadLink = ref<string>('')

  const {
    data: blob,
    refetch,
    isFetched,
  } = useQuery({
    queryKey: ['qbittorrent', downloadLink.value],
    queryFn: async () => await client.sendBlob({ url: downloadLink.value, responseType: 'blob' }),
    placeholderData: new Blob(),
    enabled: false,
    staleTime: Infinity,
  })

  const placeholder = ref<Placeholder>({
    date: new Date(),
    forum: '',
    quality: '',
    actresses: [],
    studios: [],
  })

  function setPlaceholder(forum: string, quality: string, actresses: string[], studios: string[]): void {
    placeholder.value = {
      date: new Date(),
      forum,
      quality,
      actresses,
      studios,
    }
  }

  function setDownloadLink(link: string): void {
    downloadLink.value = link
  }

  async function getBlob(): Promise<Blob> {
    if (!isFetched.value) {
      await refetch()
    }

    return blob.value!
  }

  return {
    placeholder,

    setPlaceholder,
    setDownloadLink,

    getBlob,
  }
})
