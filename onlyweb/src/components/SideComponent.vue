<script setup>
import { computed, ref, defineEmits, defineProps } from 'vue'
import { parseText } from '../services/parseText.js'
import profile from '../services/profile'
import { parseName } from '../services/parsers/name.js'
import DownloadComponent from './DownloadComponent.vue'
// TODO: DATABASE: https://www.babepedia.com/babelist.txt

const props = defineProps({
  raw: String,
  downloadLink: String,
  createdAt: String,
  seeds: String,
  duration: String,
  size: String
})

const {
  title,
  genres: unsortedGenres,
  studious: unsortedStudious,
  quality,
  year
} = parseText(props.raw)
const names = parseName(title)
const profiles = ref([])

const genres = computed(() => {
  return unsortedGenres
    .slice()
    .sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }))
})
const studious = computed(() => {
  return unsortedStudious
    .slice()
    .sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }))
})

for (const name of names) {
  profile.parameters(name).then((profile) => profiles.value.push(profile))
}

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
      ></DownloadComponent>
    </li>
    <li class="nav-item">
      <DownloadComponent
        :download-link="downloadLink"
        text="Add to queue"
        :paused="true"
      ></DownloadComponent>
    </li>
  </ul>
  <br />

  <template v-for="profile in profiles" :key="profile.name">
    <h5>
      {{ profile.name }}
    </h5>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a :href="profile.babeLink" target="_blank" rel="noreferrer">Babepedia</a>
      </li>
      <li class="nav-item">
        <a :href="profile.trackerLink" target="_blank" rel="noreferrer">Tracker</a>
      </li>
      <li class="nav-item" v-if="profile.age">Age: {{ profile.age }}</li>
      <li class="nav-item" v-if="profile.nationality">Nationality: {{ profile.nationality }}</li>
      <li class="nav-item" v-if="profile.boobs">Boobs: {{ profile.boobs }}</li>
    </ul>
    <br />
  </template>

  <h5>Info</h5>
  <ul class="nav flex-column">
    <li class="nav-item">{{ year }}</li>
  </ul>
  <ul v-if="quality" class="nav flex-column">
    <li class="nav-item">{{ quality }}</li>
  </ul>
  <ul class="nav flex-column">
    <li class="nav-item">{{ createdAt }}</li>
  </ul>
  <ul class="nav flex-column">
    <li class="nav-item">{{ size }} (↓{{ seeds }})</li>
  </ul>
  <ul v-if="duration" class="nav flex-column">
    <li class="nav-item">{{ duration }}</li>
  </ul>
  <br />

  <h5>Genres</h5>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="genre in genres" :key="genre">{{ genre }}</li>
  </ul>
  <br />

  <h5>Studios</h5>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="studio in studious" :key="studio">{{ studio }}</li>
  </ul>
</template>

<style scoped lang="scss">
.nav-item {
  font-size: 14px;
  font-weight: normal;
}
</style>
