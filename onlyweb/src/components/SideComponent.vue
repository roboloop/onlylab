<script setup>
import { computed, ref, defineEmits, defineProps, defineExpose } from 'vue'
import { parseText } from '../services/parseText.js'
import profile from '../services/profile'
import { parseName } from '../services/parsers/name.js'
import DownloadComponent from './DownloadComponent.vue'
import store from 'store'
import { formatDistance } from 'date-fns'
import _ from 'lodash'

// TODO: DATABASE: https://www.babepedia.com/babelist.txt

const props = defineProps({
  raw: String,
  topic: String,
  forums: Array,
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
const ignoredForums = import.meta.env.VITE_IGNORED_FORUMS.split(',')

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

const reloadProfile = (force = false) => {
  if (_.intersection(ignoredForums, props.forums).length > 0) {
    console.log('Ignored', ignoredForums, props.forums)
    return
  }

  profiles.value.splice(0)
  for (const name of names) {
    profile.parameters(name, force).then((profile) => profiles.value.push(profile))
  }
}
const formatDate = (date) => {
  return formatDistance(date, new Date(), { addSuffix: true })
}

const downloadedAt = store.get('downloaded:' + props.topic)

reloadProfile()
const emit = defineEmits(['exit', 'reload'])
defineExpose({ reloadProfile, profiles })
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
  </ul>
  <br />

  <template v-for="profile in profiles" :key="profile.name">
    <h5>
      {{ profile.name }}
    </h5>
    <ul class="nav flex-column">
      <li class="nav-item babepedia-icon">
        <a :href="profile.babeLink" target="_blank" rel="noreferrer">Babepedia</a>
      </li>
      <li class="nav-item tracker-icon">
        <a :href="profile.trackerLink" target="_blank" rel="noreferrer">Tracker</a>
      </li>
      <li class="nav-item" v-if="profile.age">Age: {{ profile.age }}</li>
      <li class="nav-item" v-if="profile.height">Height: {{ profile.height }}</li>
      <li class="nav-item" v-if="profile.weight">Weight: {{ profile.weight }}</li>
      <li
        class="nav-item country-icon"
        :style="{ '--flag': `'${profile.flag}'` }"
        v-if="profile.country"
      >
        Country: {{ profile.country }}
      </li>
      <li class="nav-item" v-if="profile.nationality">Nationality: {{ profile.nationality }}</li>
      <li class="nav-item" v-if="profile.boobs">Boobs: {{ profile.boobs }}</li>
      <li class="nav-item" v-if="profile.braSize">Bra size: {{ profile.braSize }}</li>
      <li class="nav-item" v-if="profile.updatedAt">
        Updated: {{ formatDate(profile.updatedAt) }}
      </li>
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
  <ul v-if="downloadedAt" class="nav flex-column">
    <li class="nav-item">{{ downloadedAt }}</li>
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

.tracker-icon {
  position: relative;

  &::before {
    content: '';
    position: absolute;
    left: -18px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    background-image: url('../assets/favicon.ico');
    background-size: contain;
    background-repeat: no-repeat;
  }
}

.babepedia-icon {
  position: relative;

  &::before {
    content: '';
    position: absolute;
    left: -18px;
    top: 50%;
    transform: translateY(-50%);
    width: 14px;
    height: 14px;
    background-image: url('../assets/babepedia.ico');
    background-size: contain;
    background-repeat: no-repeat;
  }
}

.country-icon {
  position: relative;

  &::before {
    content: var(--flag);
    position: absolute;
    left: -18px;
    top: 40%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
  }
}
</style>
