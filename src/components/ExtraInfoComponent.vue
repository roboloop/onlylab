<script setup lang="ts">
import { defineProps } from 'vue'
import { parseQuality } from '@/services/parsers/quality'
import { parseYear } from '@/services/parsers/year'
import { getTorrentStatus } from '@/services/store/torrent'
import { formatDate } from '@/services/utils/formatters'

const props = defineProps<{
  text: string
  topic: string
  createdAt: string
  seeds: string
  duration: string
  size: string
}>()

const torrentStatus = await getTorrentStatus(props.topic)
const quality = parseQuality(props.text)
const year = parseYear(props.text)
</script>

<template>
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

<style scoped lang="scss"></style>
