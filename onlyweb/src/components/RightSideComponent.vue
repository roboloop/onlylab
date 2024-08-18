<script setup>
import { computed, ref, defineProps, defineExpose } from 'vue'
import babe from '../services/clients/babe'
import hotkeys from '../services/hotkeys'
import links from '../services/links'
import name from '../services/parsers/name'
import { formatDistance } from 'date-fns'
import _ from 'lodash'
import { parse } from '../services/parsers/parser'

const props = defineProps({
  raw: String,
  forums: Array
})

const { title, genres: unsortedGenres, studious: unsortedStudious, year } = parse(props.raw)
const years = year.split('-').map((y) => Number(y.trim()))
const groupNames = name.parseNames(title)
const groupedProfiles = ref([])
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

const reloadProfile = async (force = false) => {
  if (_.intersection(ignoredForums, props.forums).length > 0) {
    console.log('Ignored', ignoredForums, props.forums)
    return
  }

  groupedProfiles.value.splice(0)
  for (const groupName of groupNames.filter((g) => g.length)) {
    const promises = groupName.map((name) => babe.profile(name, force))
    const profiles = await Promise.all(promises)

    const mainName = groupName[0]
    const [nonNullGroup, nullGroup] = _.partition(profiles, (p) => !!p.babeName)
    const grouped = nonNullGroup.length
      ? Object.entries(_.groupBy(nonNullGroup, (p) => p.babeName)).map(([n, p]) => [
          n,
          [...p, ...nullGroup]
        ])
      : Object.entries({ [mainName]: nullGroup })
    for (const [babeName, profiles] of grouped) {
      const profile = profiles.find((p) => p.babeName) ?? profiles[0]
      const age = profile.age && profile.age.match(/\d+/)?.[0]
      groupedProfiles.value.push({
        name: mainName,
        babeName: babeName,
        aliases: _.uniq(
          [...profiles, { name: babeName }].map((p) => p.name).filter((n) => n !== mainName)
        ),
        profile: profile,
        // TODO: refactor
        ages: age ? years.map((y) => +age - (new Date().getFullYear() - +y)) : []
      })
    }
  }
}
const formatDate = (date) => {
  return formatDistance(date, new Date(), { addSuffix: true })
}

reloadProfile()
defineExpose({ reloadProfile })

hotkeys.register('KeyB', 'Open the first babepedia link', { ctrlKey: true }, () => {
  const name = groupedProfiles.value[0]?.babeName
  if (name) {
    window.open(links.babepediaLink(name), '_blank')
  }
})
hotkeys.register('KeyL', 'Open the first tracker search link', { ctrlKey: true }, () => {
  const name = groupedProfiles.value[0]?.name
  if (name) {
    window.open(links.trackerSearchLink(name), '_blank')
  }
})
</script>

<template>
  <template v-for="{ name, babeName, aliases, profile, ages } in groupedProfiles" :key="name">
    <h5>
      {{ name }}
    </h5>
    <ul class="nav flex-column">
      <li class="nav-item" v-for="alias in aliases" :key="alias">
        {{ alias }}
      </li>
      <li class="nav-item babepedia-icon">
        <a :href="links.babepediaLink(babeName)" target="_blank" rel="noreferrer">Babepedia</a>
      </li>
      <li class="nav-item tracker-icon">
        <a :href="links.trackerSearchLink(profile.name)" target="_blank" rel="noreferrer">Tracker</a>
      </li>
      <li class="nav-item">Age: {{ profile.age || '—' }}</li>
      <li class="nav-item" v-if="ages.length">Acts: {{ ages.join('-') }}</li>
      <li class="nav-item">Height: {{ profile.height || '—' }}</li>
      <li class="nav-item">Weight: {{ profile.weight || '—' }}</li>
      <li
        class="nav-item country-icon"
        :style="{ '--flag': `'${profile.flag}'` }"
        v-if="profile.country"
      >
        Country: {{ profile.country }}
      </li>
      <li class="nav-item country-icon" v-else>Country: {{ '—' }}</li>
      <li class="nav-item">Nationality: {{ profile.nationality || '—' }}</li>
      <li class="nav-item">Boobs: {{ profile.boobs || '—' }}</li>
      <li class="nav-item">Bra size: {{ profile.braSize || '—' }}</li>
      <li class="nav-item">
        Updated: {{ profile.updatedAt ? formatDate(profile.updatedAt) : '—' }}
      </li>
    </ul>
    <br />
  </template>

  <h5>Genres</h5>
  <span v-if="!genres.length"><i>No genres</i></span>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="genre in genres" :key="genre">
      <a :href="links.trackerSearchLink(genre)" target="_blank" rel="noreferrer">{{ genre }}</a>
    </li>
  </ul>
  <br />

  <h5>Studios</h5>
  <span v-if="!studious.length"><i>No studious</i></span>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="studio in studious" :key="studio">{{ studio }}</li>
  </ul>
</template>

<style scoped lang="scss">
@import '../assets/sidebar/shared.scss';
</style>
