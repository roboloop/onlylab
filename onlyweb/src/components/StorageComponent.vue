<script setup>
import trashCan from '@/assets/trash.svg'
import storage from '@/services/storage.js'
import { format } from 'date-fns'
import { ref } from 'vue'
import LinkComponent from './LinkComponent.vue'
import _ from 'lodash'

const onDump = () => {
  try {
    const json = JSON.stringify(storage.readAll(), null, 2)
    const blob = new Blob([json], { type: 'application/json' })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = `${format(new Date(), 'dd.MM.yyyy HH_mm_ss')}-dump.json`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
  } catch (e) {
    console.error(e)
    return false
  }
  return true
}

const uploadRef = ref(null)
let resolveOnUpload
const onUpload = async (event) => {
  const file = event.target.files[0]
  if (!file) {
    resolveOnUpload(false)
    return
  }

  try {
    const reader = new FileReader()
    reader.onload = () => {
      try {
        const data = JSON.parse(reader.result)
        storage.writeAll(data)
        resolveOnUpload(true)
        onStatistics()
      } catch (error) {
        console.error(error)
        resolveOnUpload(false)
      }
    }
    reader.onerror = () => {
      console.error(reader.error)
      resolveOnUpload(false)
    }

    reader.readAsText(file)
  } catch (error) {
    console.error(error)
    resolveOnUpload(false)
  }
}

const uploadHandler = async () => {
  uploadRef.value.click()
  return new Promise((resolve) => {
    resolveOnUpload = resolve
  })
}

const statistics = ref([])
const onStatistics = () => {
  statistics.value.splice(0)
  statistics.value = Object.entries(storage.stat())
  console.log(statistics.value)
}

const onTrash = (type) => {
  switch (type) {
    case 'total':
      storage.removeAll()
      break
    case 'images':
      storage.removeImgs()
      break
    default:
      break
  }
  onStatistics()
}
</script>
<template>
  <h5>Storage</h5>
  <ul class="nav flex-column">
    <li class="nav-item">
      <LinkComponent text="Dump" :handler="onDump"></LinkComponent>
    </li>
    <li class="nav-item">
      <input type="file" ref="uploadRef" @change="onUpload" class="d-none" />
      <LinkComponent text="Upload" :handler="uploadHandler" class="file-link"></LinkComponent>
    </li>
    <li class="nav-item">
      <a href="#" target="_blank" @click.prevent.stop="onStatistics">Statistics</a>
      <ul class="nav flex-column mt-0" v-if="statistics">
        <li
          class="nav-item d-flex align-items-center ml-0"
          v-for="[type, size] in statistics"
          :key="type"
        >
          <img :src="trashCan" alt="" @click="() => onTrash(type)" class="trash-icon" />
          <span>{{ _.startCase(type) }}: {{ size }}</span>
        </li>
      </ul>
    </li>
  </ul>
  <br />
</template>

<style scoped lang="scss">
@import '../assets/sidebar/shared.scss';
.trash-icon {
  width: 16px;
  height: 16px;
  margin-right: 4px;
  cursor: pointer;
}
</style>
