<script setup lang="ts">
import BiBoxArrowUpRight from '~icons/bi/box-arrow-up-right?width=0.75em&height=0.75em'
import { BLink, vBTooltip } from 'bootstrap-vue-next'
import { ref } from 'vue'
import { formatDate } from '@/services/utils/formatters'
import * as links from '@/services/utils/links'

import type { NormalizedProfile } from '@/services/babepedia/actress'

const props = defineProps<{
  profile: NormalizedProfile
  year: string
}>()

const years = props.year.split('-').map(y => Number(y.trim()))
const age = props.profile.age?.match(/\d+/)?.[0]
const agesOnVideo = age ? years.map(y => +age - (new Date().getFullYear() - +y)) : []
const tooltip = props.profile.updatedAt ? `Updated: ${formatDate(props.profile.updatedAt)}` : ''
const truncate = ref<boolean>(true)
</script>

<template>
  <h5 v-b-tooltip.left="tooltip">
    {{ profile.mainName }}
  </h5>
  <ul class="nav flex-column">
    <li class="nav-item" v-for="alias in profile.aliases" :key="alias">
      {{ alias }}
    </li>
    <li class="nav-item babepedia-icon">
      <BLink
        icon
        :href="links.babepediaLink(profile.babeName ?? profile.name)"
        target="_blank"
        rel="noreferrer"
        :title="`Check out &quot;${profile.babeName ?? profile.name}&quot; on Babepedia`">
        Babepedia
        <BiBoxArrowUpRight />
      </BLink>
    </li>
    <li class="nav-item tracker-icon">
      <BLink
        icon
        :href="links.trackerSearchLink(profile.name)"
        target="_blank"
        rel="noreferrer"
        :title="`Search for &quot;${profile.name}&quot; on Tracker`">
        Tracker
        <BiBoxArrowUpRight />
      </BLink>
    </li>
    <li class="nav-item">Age: {{ profile.age || '—' }}</li>
    <li class="nav-item" v-if="agesOnVideo.length">On video: {{ agesOnVideo.join('-') }} years old</li>
    <li class="nav-item">Height: {{ profile.height || '—' }}</li>
    <li class="nav-item">Weight: {{ profile.weight || '—' }}</li>
    <li class="nav-item country-icon" :style="{ '--flag': `'${profile.flag}'` }" v-if="profile.country">
      Country: {{ profile.country }}
    </li>
    <li class="nav-item country-icon" v-else>Country: {{ '—' }}</li>
    <li class="nav-item">Nationality: {{ profile.nationality || '—' }}</li>
    <li class="nav-item">Boobs: {{ profile.boobs || '—' }}</li>
    <li class="nav-item">Bra size: {{ profile.braSize || '—' }}</li>
    <li class="nav-item">Body type: {{ profile.bodyType || '—' }}</li>
    <li class="nav-item" :class="{ truncate: truncate }" @mouseenter="truncate = false">
      Tattoos: {{ profile.tattoos || '—' }}
    </li>
    <li class="nav-item" :class="{ truncate: truncate }" @mouseenter="truncate = false">
      Piercings: {{ profile.piercings || '—' }}
    </li>
  </ul>
  <br />
</template>

<style scoped lang="scss">
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  display: inline-block;
  width: 100%;
}
</style>
