<script setup lang="ts">
import BiArrowRight from '~icons/bi/arrow-right'
import { BLink, useToast } from 'bootstrap-vue-next'
import { defineProps } from 'vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { client } from '@/services/clients/client'
import * as qbittorrent from '@/services/clients/qbittorrent'
import { parseName } from '@/services/parsers/name'
import { parseQuality } from '@/services/parsers/quality'
import { parseStudio } from '@/services/parsers/studio'
import { parseTitle } from '@/services/parsers/title'
import * as torrent from '@/services/store/torrent'

import type { Placeholder } from '@/services/utils/strings'

const props = defineProps<{
  text: string
  topic: string
  forumName: string
  downloadLink: string
}>()

const quality = parseQuality(props.text)
const actresses = parseName(parseTitle(props.text))
const studios = parseStudio(props.text)

const { create } = useToast()

async function downloadTorrent(paused: boolean): Promise<void> {
  const placeholder: Placeholder = {
    date: new Date(),
    forum: props.forumName,
    quality,
    actresses,
    studios,
  }
  try {
    const blob = await client.sendBlob({ url: props.downloadLink, responseType: 'blob' })
    await qbittorrent.upload(blob, paused, placeholder)
  } catch (err) {
    create({
      title: 'Failed',
      variant: 'danger',
      body: (err as Error).message,
      noProgress: true,
    })
    return
  }

  await torrent.markAsDownloaded(props.topic)

  const title = paused ? 'Torrent has been added to queue' : 'Torrent has been added'
  create({
    title: title,
    variant: 'success',
    noProgress: true,
  })
}

async function removeTorrent(): Promise<void> {
  try {
    const blob = await client.sendBlob({ url: props.downloadLink, responseType: 'blob' })
    await qbittorrent.remove(blob)
  } catch (err) {
    create({
      title: 'Failed',
      variant: 'danger',
      body: (err as Error).message,
      noProgress: true,
    })
    return
  }

  await torrent.markAsRemoved(props.topic)

  create({
    title: 'Torrent has been removed',
    variant: 'success',
    noProgress: true,
  })
}

const { registerDownload, registerAddToQueue, registerRemove } = useHotkeys()
registerDownload(() => downloadTorrent(false))
registerAddToQueue(() => downloadTorrent(true))
registerRemove(() => removeTorrent())
</script>

<template>
  <h5>Qbittorrent</h5>
  <ul class="nav flex-column">
    <li class="nav-item">
      <BLink icon href="#" target="_blank" @click.prevent.stop="downloadTorrent(false)">
        Download
        <BiArrowRight />
      </BLink>
    </li>
    <li class="nav-item">
      <BLink icon href="#" target="_blank" @click.prevent.stop="downloadTorrent(true)">
        Add to queue
        <BiArrowRight />
      </BLink>
    </li>
    <li class="nav-item">
      <BLink icon href="#" target="_blank" @click.prevent.stop="removeTorrent">
        Remove
        <BiArrowRight />
      </BLink>
    </li>
  </ul>
  <br />
</template>

<style scoped lang="scss"></style>
