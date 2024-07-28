<script setup>
import { defineEmits, defineProps } from 'vue'
import { parseText } from '../services/parseText'
import DownloadComponent from './DownloadComponent.vue'
import storage from '../services/storage'
import { formatDistance } from 'date-fns'
import links from '../services/links'

const props = defineProps({
  raw: String,
  topic: String,
  downloadLink: String,
  createdAt: String,
  seeds: String,
  duration: String,
  size: String
})

const { title, quality, year } = parseText(props.raw)

const formatDate = (date) => {
  return formatDistance(date, new Date(), { addSuffix: true })
}

const downloadedAt = storage.getDownloaded(props.topic)

const emit = defineEmits(['exit', 'reload'])
</script>

<template>
  <h5>Options</h5>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="#" target="_blank" @click.prevent.stop="emit('exit')">Exit</a>
    </li>
    <li class="nav-item">
      <a href="#" target="_blank" @click.prevent.stop="emit('reload')">Reload</a>
    </li>
    <li class="nav-item">
      <DownloadComponent
        :download-link="downloadLink"
        text="Download"
        :paused="false"
        :topic="props.topic"
      ></DownloadComponent>
    </li>
    <li class="nav-item">
      <DownloadComponent
        :download-link="downloadLink"
        text="Add to queue"
        :paused="true"
        :topic="props.topic"
      ></DownloadComponent>
    </li>
    <li class="nav-item tracker-icon">
      <a :href="links.trackerSearchLink(title)" target="_blank" rel="noreferrer">Related</a>
    </li>
  </ul>
  <br />

  <h5>Info</h5>
  <ul class="nav flex-column">
    <li class="nav-item">Year: {{ year }}</li>
  </ul>
  <ul v-if="quality" class="nav flex-column">
    <li class="nav-item">Quality: {{ quality }}</li>
  </ul>
  <ul class="nav flex-column">
    <li class="nav-item">Created: {{ createdAt }}</li>
  </ul>
  <ul class="nav flex-column">
    <li class="nav-item">Size: {{ size }} (↓{{ seeds }})</li>
  </ul>
  <ul v-if="duration" class="nav flex-column">
    <li class="nav-item">Length: {{ duration }}</li>
  </ul>
  <ul v-if="downloadedAt" class="nav flex-column">
    <li class="nav-item">Downloaded: {{ formatDate(downloadedAt) }}</li>
  </ul>
  <br />
</template>

<style scoped lang="scss">
@import '../assets/sidebar/shared.scss';
</style>
