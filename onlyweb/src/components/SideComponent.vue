<script setup>
import { computed, ref, defineEmits, defineProps } from 'vue'
import { parseText } from '../services/parseText.js'
import profile from '../services/profile'
import { parseName } from '../services/parsers/name.js'

const props = defineProps({
  raw: String,
  downloadLink: String,
  createdAt: String,
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

// profile.parameters(profileLink).then(({boobs: boobsParams}) => boobs.value = boobsParams ? boobsParams : '<no data>')

const emit = defineEmits(['exit'])
// TODO: DATABASE: https://www.babepedia.com/babelist.txt
</script>

<template>
  <h5>Options</h5>

  <ul class="nav flex-column">
    <li class="nav-item">
      <a href="#" target="_blank" @click.prevent.stop="emit('exit')">Exit</a>
    </li>
    <li class="nav-item">
      <a :href="downloadLink" target="_blank">Download</a>
    </li>
  </ul>
  <br />

  <template v-for="profile in profiles" :key="profile.name">
    <h5>
      <a :href="profile.link" target="_blank">{{ profile.name }}</a>
    </h5>
    <ul class="nav flex-column">
      <!--      <li class="nav-item"><a :href="profile.link" target="_blank">Babepedia</a></li>-->
      <li class="nav-item">Age: {{ profile.age }}</li>
      <li class="nav-item">Nationality: {{ profile.nationality }}</li>
      <li class="nav-item">Boobs: {{ profile.boobs }}</li>
    </ul>
  </template>

  <h5>Info</h5>
  <ul class="nav flex-column">
    <li class="nav-item">{{ year }}</li>
  </ul>
  <ul v-if="quality" class="nav flex-column">
    <li class="nav-item">{{ quality }}</li>
  </ul>
  <i v-else>no quality</i>
  <ul class="nav flex-column">
    <li class="nav-item">{{ createdAt }}</li>
  </ul>
  <ul class="nav flex-column">
    <li class="nav-item">{{ size }}</li>
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
