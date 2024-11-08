<script setup lang="ts">
import { defineProps } from 'vue'
import QbittorrentComponent from '@/components/QbittorrentComponent.vue'
import ViewModeComponent from '@/components/ViewModeComponent.vue'
import { parseQuality } from '@/services/parsers/quality'
import { parseYear } from '@/services/parsers/year'
import { getSettings } from '@/services/store/settings'
import { getTorrentStatus } from '@/services/store/torrent'
import { formatDate } from '@/services/utils/formatters'
import {BListGroup, BListGroupItem} from 'bootstrap-vue-next'

import type { ImageNode } from '@/services/dom/topic'

const props = defineProps<{
  text: string
  topic: string
  forumName: string
  downloadLink: string
  createdAt: string
  seeds: string
  duration: string
  size: string
  imageNodes: ImageNode[]
}>()

const {
  qbittorrent: { enable: qbittorrentEnabled },
} = await getSettings()

const torrentStatus = await getTorrentStatus(props.topic)

const quality = parseQuality(props.text)
const year = parseYear(props.text)
</script>

<template>
  <ViewModeComponent
    :topic="topic"
    :text="text"
    :imageNodes="imageNodes"
  />

  <QbittorrentComponent
    v-if="qbittorrentEnabled"
    :text="props.text"
    :topic="props.topic"
    :forum-name="props.forumName"
    :download-link="props.downloadLink"
  />

  <h5>Info</h5>
  <ul class="nav flex-column">
    <li>Year: {{ year }}</li>
    <li v-if="quality">Quality: {{ quality }}</li>
    <li>Created: {{ createdAt }}</li>
    <li>Size: {{ size }} (↓{{ seeds }})</li>
    <li v-if="duration">Duration: {{ duration }}</li>
    <li v-if="torrentStatus.downloadedAt">Downloaded: {{ formatDate(torrentStatus.downloadedAt) }}</li>
  </ul>
  <br />
</template>

<style scoped lang="scss">
</style>
