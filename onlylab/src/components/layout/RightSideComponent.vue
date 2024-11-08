<script setup lang="ts">
import BiBoxArrowUpRight from '~icons/bi/box-arrow-up-right?width=0.75em&height=0.75em'
import { BLink } from 'bootstrap-vue-next'
import _ from 'lodash'
import {defineExpose, defineProps, ref, watch, watchEffect, computed} from 'vue'
import { formatDate } from '@/services/utils/formatters'
import { useHotkeys } from '@/composables/useHotkeys'
import { normalizeProfiles } from '@/services/babepedia/actress'
import { parseGenre } from '@/services/parsers/genre'
import { parseNames } from '@/services/parsers/name'
import { parseStudio } from '@/services/parsers/studio'
import { parseTitle } from '@/services/parsers/title'
import { parseYear } from '@/services/parsers/year'
import { getSettings } from '@/services/store/settings'
import { localSort } from '@/services/utils/array'
import * as links from '@/services/utils/links'

import type { NormalizedProfile } from '@/services/babepedia/actress'
import {useProfiles} from "@/composables/useProfiles";

interface ShownProfile extends NormalizedProfile {
  ages: number[]
}

const props = defineProps<{
  text: string
  forums: string[]
}>()

const genres = localSort(parseGenre(props.text))
const studious = localSort(parseStudio(props.text))
const title = parseTitle(props.text)
const year = parseYear(props.text)
const years = year.split('-').map(y => Number(y.trim()))

const {normalizedProfiles} = useProfiles(props.text)

const groupedProfiles = computed(() => {
  return normalizedProfiles?.value?.map(profile => {
    const age = profile.age?.match(/\d+/)?.[0]
    return {
      ...profile,
      ages: age ? years.map(y => +age - (new Date().getFullYear() - +y)) : [],
    } as ShownProfile
  })
})

const { registerHotkey } = useHotkeys()
registerHotkey({ mac: 'control+B', win: 'alt+B' }, 'Open the first babepedia link', () => {
  const name = groupedProfiles?.value?.[0]?.babeName
  if (name) {
    window.open(links.babepediaLink(name), '_blank')
  }
})
registerHotkey({ mac: 'control+L', win: 'alt+L' }, 'Open the first tracker search link', () => {
  const name = groupedProfiles?.value?.[0]?.name
  if (name) {
    window.open(links.trackerSearchLink(name), '_blank')
  }
})
</script>

<template>
  <template v-for="p in groupedProfiles" :key="p.babeName">
    <h5>
      {{ p.mainName }}
    </h5>
    <ul class="nav flex-column">
      <li class="nav-item" v-for="alias in p.aliases" :key="alias">
        {{ alias }}
      </li>
      <li class="nav-item babepedia-icon">
        <BLink icon :href="links.babepediaLink(p.babeName ?? p.name)" target="_blank" rel="noreferrer" :title='`Check out "${p.babeName ?? p.name}" on Babepedia`'>
          Babepedia
          <BiBoxArrowUpRight />
        </BLink>
      </li>
      <li class="nav-item tracker-icon">
        <BLink icon :href="links.trackerSearchLink(p.name)" target="_blank" rel="noreferrer" :title='`Search for "${p.name}" on Tracker`'>
          Tracker
          <BiBoxArrowUpRight />
        </BLink>
      </li>
      <li class="nav-item">Age: {{ p.age || '—' }}</li>
      <li class="nav-item" v-if="p.ages.length">On video: {{ p.ages.join('-') }} years old</li>
      <li class="nav-item">Height: {{ p.height || '—' }}</li>
      <li class="nav-item">Weight: {{ p.weight || '—' }}</li>
      <li class="nav-item country-icon" :style="{ '--flag': `'${p.flag}'` }" v-if="p.country">
        Country: {{ p.country }}
      </li>
      <li class="nav-item country-icon" v-else>Country: {{ '—' }}</li>
      <li class="nav-item">Nationality: {{ p.nationality || '—' }}</li>
      <li class="nav-item">Boobs: {{ p.boobs || '—' }}</li>
      <li class="nav-item">Bra size: {{ p.braSize || '—' }}</li>
      <li class="nav-item">Updated: {{ p.updatedAt ? formatDate(p.updatedAt) : '—' }}</li>
    </ul>
    <br />
  </template>

  <h5>Genres</h5>
  <span v-if="!genres.length"><i>No genres</i></span>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="genre in genres" :key="genre">
      <BLink icon :href="links.trackerSearchLink(genre)" target="_blank" rel="noreferrer" :title='`Search for "${genre}" on Tracker`'>
        {{ genre }}
        <BiBoxArrowUpRight />
      </BLink>
    </li>
  </ul>
  <br />

  <h5>Studios</h5>
  <span v-if="!studious.length"><i>No studious</i></span>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="studio in studious" :key="studio">
      <BLink icon :href="links.trackerSearchLink(studio)" target="_blank" rel="noreferrer" :title='`Search for "${studio}" on Tracker`'>
        {{ studio }}
        <BiBoxArrowUpRight />
      </BLink>
    </li>
  </ul>
</template>

<style scoped lang="scss">

</style>
