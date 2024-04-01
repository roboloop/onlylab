<script setup>
import { computed, ref, defineEmits, defineProps } from 'vue'
import { parseText } from '../services/parseText.js'
import profile from '../services/profile'
import { parseName } from '../services/parsers/name.js'
import qbit from '../services/qbit'
import { BBadge } from 'bootstrap-vue'
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
const onDownload = async () => {
  const today = new Date()
  const day = ('0' + today.getDate()).slice(-2)
  const month = ('0' + (today.getMonth() + 1)).slice(-2)
  const folder = day + month
  const result = await qbit.upload(props.downloadLink, folder)

  showSuccessBadge.value = result
  showWarningBadge.value = !result

  setTimeout(() => {
    showSuccessBadge.value = false
    showWarningBadge.value = false
  }, 5000)
}
const showSuccessBadge = ref(false)
const showWarningBadge = ref(false)
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
      <a :href="downloadLink" target="_blank" @click.prevent.stop="onDownload">Download</a>
      <Transition appear>
        <b-badge variant="success" :pill="true" style="margin-left: 12px" v-if="showSuccessBadge"
          >Success</b-badge
        >
      </Transition>
      <Transition appear>
        <b-badge variant="warning" :pill="true" style="margin-left: 12px" v-if="showWarningBadge"
          >Fail</b-badge
        >
      </Transition>
    </li>
  </ul>
  <br />

  <template v-for="profile in profiles" :key="profile.name">
    <h5>
      <a :href="profile.link" target="_blank" rel="noreferrer">{{ profile.name }}</a>
    </h5>
    <ul class="nav flex-column">
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
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

.nav-item {
  font-size: 14px;
  font-weight: normal;
}
</style>
