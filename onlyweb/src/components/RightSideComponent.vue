<script setup>
import { computed, ref, defineProps, defineExpose } from 'vue'
import profile from '../services/clients/babe'
import links from '../services/links'
import { parseName } from '../services/parsers/name'
import { formatDistance } from 'date-fns'
import _ from 'lodash'
import { parse } from '../services/parsers/parser'

const props = defineProps({
  raw: String,
  forums: Array
})

const { title, genres: unsortedGenres, studious: unsortedStudious } = parse(props.raw)
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

reloadProfile()
defineExpose({ reloadProfile, profiles })
</script>

<template>
  <template v-for="profile in profiles" :key="profile.name">
    <h5>
      {{ profile.name }}
    </h5>
    <ul class="nav flex-column">
      <li class="nav-item babepedia-icon">
        <a :href="links.babepediaLink(profile.name)" target="_blank" rel="noreferrer">Babepedia</a>
      </li>
      <li class="nav-item tracker-icon">
        <a :href="links.trackerSearchLink(profile.name)" target="_blank" rel="noreferrer">Tracker</a>
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

  <h5>Genres</h5>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="genre in genres" :key="genre">
      <a :href="links.trackerSearchLink(genre)" target="_blank" rel="noreferrer">{{ genre }}</a>
    </li>
  </ul>
  <br />

  <h5>Studios</h5>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="studio in studious" :key="studio">{{ studio }}</li>
  </ul>
</template>

<style scoped lang="scss">
@import '../assets/sidebar/shared.scss';
</style>
