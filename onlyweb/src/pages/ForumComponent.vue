<script setup>
import { ref, watch } from 'vue'
import _ from 'lodash'
import storage from '../services/storage'
import escapeStringRegexp from 'escape-string-regexp'
import hotkeys from '../services/hotkeys'
import { parse } from '../services/parsers/parser'
import name from '../services/parsers/name.js'

const handleAllTopics = (fn) =>
  Array.from(document.querySelectorAll('.forumline tbody tr:has(> td.tCenter)')).reduce(
    (acc, el) => acc + (fn(el) ?? 0),
    0
  )
const handleBannedTopics = (fn) =>
  Array.from(document.querySelectorAll('.forumline tbody tr:has(> td.fade-out)')).reduce(
    (acc, el) => acc + (fn(el) ?? 0),
    0
  )
const handleNotBannedTopics = (fn) =>
  Array.from(
    document.querySelectorAll('.forumline tbody tr:has(> td.tCenter):not(:has(.fade-out))')
  ).reduce((acc, el) => acc + (fn(el) ?? 0), 0)

const totalHidden = ref(0)
const totalBanned = ref(0)

// Open in a new tab
handleAllTopics((tr) => (tr.querySelector('a').target = '_blank'))

// Fading out and redify banned
totalBanned.value = handleAllTopics((tr) => {
  const bannedWords = (raw) => {
    const bannedGenres = import.meta.env.VITE_BANNED_GENRES.split(',')
    const bannedStudious = import.meta.env.VITE_BANNED_STUDIOUS.split(',')

    const { genres, studious } = parse(raw)
    const commonGenres = _.intersection(
      genres.map((i) => i.toLowerCase()),
      bannedGenres.map((i) => i.toLowerCase())
    )
    const commonStudious = _.intersection(
      studious.map((i) => i.toLowerCase()),
      bannedStudious.map((i) => i.toLowerCase())
    )

    return [...commonGenres, ...commonStudious]
  }

  const textElement = tr.querySelector('.tLink,.tt-text')
  const raw = textElement.textContent
  const words = bannedWords(raw)
  if (words.length) {
    const escaped = words.map((w) => escapeStringRegexp(w)).join('|')
    textElement.innerHTML = raw.replaceAll(
      // The last part of regex is a hack for genres that contain `)` at the end
      new RegExp('\\b(' + escaped + ')(?:\\\\b|[^\\w])', 'gi'),
      '<span class="banned">$1</span>'
    )
    Array.from(tr.children).forEach((td) => td.classList.add('fade-out'))
    return true
  }
})

// Add fake books info
handleAllTopics((tr) => {
  const createPill = (text) => {
    const template = document.createElement('template')
    template.innerHTML = `<span style="font-size: 10px;" class="badge badge-warning badge-pill">${text}</span>`
    return template.content.children[0]
  }
  const textElement = tr.querySelector('.tLink,.tt-text')
  const { title } = parse(textElement.textContent)
  const names = name.parseName(title)
  const allFakeBoobs = names.every((name) => {
    const profile = storage.getProfile(name)
    if (!profile || !profile.boobs) {
      return false
    }
    return !!profile.boobs.match(/fake/i)
  })
  if (names.length > 0 && allFakeBoobs) {
    const pill = createPill('Fake boobs')
    tr.querySelector('a[class*="bold"]').parentElement.appendChild(pill)
  }
})

// Apply filter
const applyFilter = (filter) => {
  handleAllTopics((tr) => (tr.style.display = ''))
  handleNotBannedTopics(
    (tr) =>
      (tr.querySelector('.tLink,.tt-text').innerHTML =
        tr.querySelector('.tLink,.tt-text').textContent)
  )
  if (!filter) {
    totalHidden.value = 0
    return
  }

  const hiddenBanned = handleBannedTopics((tr) => {
    tr.style.display = 'none'
    return true
  })
  const hiddenFiltered = handleNotBannedTopics((tr) => {
    const raw = tr.querySelector('.tLink,.tt-text').textContent
    const words = filter
      .split(';')
      .map((w) => w.trim())
      .filter(Boolean)
    const every = words.every((w) => raw.toLowerCase().includes(w.toLowerCase()))
    if (every) {
      const escaped = words.map((w) => escapeStringRegexp(w)).join('|')
      tr.querySelector('.tLink,.tt-text').innerHTML = raw.replaceAll(
        new RegExp('(' + escaped + ')', 'gi'),
        '<span class="filter">$1</span>'
      )
    } else {
      tr.style.display = 'none'
      return true
    }
  })
  totalHidden.value = hiddenBanned + hiddenFiltered
}

const filter = storage.getFilter() ?? ''
const input = ref(filter)
const inputRef = ref(null)
watch(input, (filter) => {
  applyFilter(filter)
  storage.putFilter(filter)
  isShowAll = false
})
let isShowAll = false
const toggleFilter = () => {
  applyFilter(isShowAll ? filter : '')
  isShowAll = !isShowAll
}
applyFilter(filter)

hotkeys.register('KeyF', 'Focus on search line', { ctrlKey: true }, () => inputRef.value.focus())
hotkeys.register('KeyS', 'Show all topics', { ctrlKey: true }, () => toggleFilter())
hotkeys.register('ArrowLeft', 'Previous page', { altKey: true }, () =>
  document.querySelector('.bottom_info .nav a:nth-child(2)').click()
)
hotkeys.register('ArrowRight', 'Next page', { altKey: true }, () =>
  document.querySelector('.bottom_info .nav a:last-child').click()
)
</script>

<template>
  <div class="fixed-bottom-bar">
    <input
      type="text"
      class="filter-input"
      placeholder="Enter filter..."
      v-model="input"
      ref="inputRef"
    />
    <div>
      <img src="../assets/eye-regular.svg" class="icon" alt="" @click="toggleFilter" />
      <span @click="toggleFilter">Hidden topics: {{ totalHidden }}.</span>
      <span>Banned topics: {{ totalBanned }}.</span>
    </div>
  </div>
</template>

<style scoped>
.fixed-bottom-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #333;
  color: #fff;
  padding: 10px;
  box-sizing: border-box;
  z-index: 99;
}

.icon {
  cursor: pointer;
  width: 20px;
  height: 20px;
  filter: invert(100%);
  padding-bottom: 4px;
}

.filter-input {
  width: calc(100% - 20px);
  padding: 8px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
}
</style>
