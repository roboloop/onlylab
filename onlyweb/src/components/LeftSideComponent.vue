<script setup>
import { defineEmits, defineProps, ref } from 'vue'
import LinkComponent from './LinkComponent.vue'
import storage from '../services/storage'
import { format, formatDistance } from 'date-fns'
import links from '../services/links'
import { parse } from '../services/parsers/parser.js'
import qbit from '../services/clients/qbit.js'
import tracker from '../services/clients/tracker.js'
import hotkeys from '../services/hotkeys.js'

const props = defineProps({
  raw: String,
  topic: String,
  downloadLink: String,
  createdAt: String,
  seeds: String,
  duration: String,
  size: String
})

const { title, quality, year } = parse(props.raw)

const formatDate = (date) => {
  return formatDistance(date, new Date(), { addSuffix: true })
}

const downloadHandler = async (paused) => {
  const today = new Date()
  const folder = format(today, 'MMyy')
  const result = await qbit.upload(props.topic, props.downloadLink, folder, paused)
  console.log('downloadHandler', result)
  if (result) {
    storage.putDownloaded(props.topic)
  }

  return result
}

const removeHandler = async () => {
  const result = await qbit.delete(props.topic)
  if (result) {
    storage.removeDownloaded(props.topic)
  }

  return result
}

const downloadedAt = storage.getDownloaded(props.topic)

const downloadRef = ref(null)
const addToQueueRef = ref(null)
hotkeys.register('KeyD', 'Download topic', { ctrlKey: true }, () => downloadRef.value.onClick())
hotkeys.register('KeyQ', 'Add topic to queue', { ctrlKey: true }, () =>
  addToQueueRef.value.onClick()
)

const emit = defineEmits(['exit', 'reload', 'topicImages', 'commentImages'])

const files = ref([])
const showFiles = ref(true)
const onShowFiles = async () => {
  if (!showFiles.value) {
    return
  }
  files.value = await tracker.files(props.topic)
  showFiles.value = false
}
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
      <a href="#" target="_blank" @click.prevent.stop="emit('topicImages')">Topic images</a>
    </li>
    <li class="nav-item">
      <a href="#" target="_blank" @click.prevent.stop="emit('commentImages')">Comment images</a>
    </li>
    <li class="nav-item">
      <LinkComponent
        v-if="downloadLink"
        text="Download"
        :handler="() => downloadHandler(false)"
        ref="downloadRef"
      ></LinkComponent>
    </li>
    <li class="nav-item">
      <LinkComponent
        v-if="downloadLink"
        text="Add to queue"
        :handler="() => downloadHandler(true)"
        ref="addToQueueRef"
      ></LinkComponent>
    </li>
    <li class="nav-item">
      <LinkComponent text="Remove" :handler="removeHandler"></LinkComponent>
    </li>
    <li class="nav-item tracker-icon">
      <a :href="links.trackerSearchLink(title)" target="_blank" rel="noreferrer">Related</a>
    </li>
  </ul>
  <br />

  <h5>Info</h5>
  <ul class="nav flex-column">
    <li class="nav-item">Year: {{ year }}</li>
    <li class="nav-item" v-if="quality">Quality: {{ quality }}</li>
    <li class="nav-item">Created: {{ createdAt }}</li>
    <li class="nav-item">Size: {{ size }} (↓{{ seeds }})</li>
    <li class="nav-item" v-if="duration">Length: {{ duration }}</li>
    <li class="nav-item" v-if="downloadedAt">Downloaded: {{ formatDate(downloadedAt) }}</li>
  </ul>
  <br />

  <h5>Files</h5>
  <ul class="nav flex-column" v-if="showFiles">
    <li class="nav-item">
      <a href="#" @click.prevent.stop="onShowFiles">Show files</a>
    </li>
  </ul>

  <ul class="nav flex-column" style="list-style: inherit">
    <li class="nav-item" style="font-size: 12px" v-for="{ name, size } of files" :key="name + size">
      [{{ size }}] {{ name }}
    </li>
  </ul>
  <br />
</template>

<style scoped lang="scss">
@import '../assets/sidebar/shared.scss';
</style>
