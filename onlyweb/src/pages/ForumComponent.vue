<script setup>
import { ref, watch } from 'vue'
import { parseText } from '../services/parseText.js'
import _ from 'lodash'
import storage from '../services/storage'
import escapeStringRegexp from 'escape-string-regexp'
import hotkeys from '../services/hotkeys'

const handleAllTopics = (fn) =>
  document.querySelectorAll('.forumline tbody tr:has(> td.tCenter)').forEach((el) => fn(el))
const handleBannedTopics = (fn) => {
  document.querySelectorAll('.forumline tbody tr:has(> td.fade-out)').forEach((el) => fn(el))
}
const handleNotBannedTopics = (fn) => {
  document
    .querySelectorAll('.forumline tbody tr:has(> td.tCenter):not(:has(.fade-out))')
    .forEach((el) => fn(el))
}

// Open in a new tab
handleAllTopics((tr) => (tr.querySelector('a').target = '_blank'))

// Fading out and redify banned
handleAllTopics((tr) => {
  const bannedWords = (raw) => {
    const bannedGenres = import.meta.env.VITE_BANNED_GENRES.split(',')
    const bannedStudious = import.meta.env.VITE_BANNED_STUDIOUS.split(',')

    const { genres, studious } = parseText(raw)
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
    return
  }

  handleBannedTopics((tr) => (tr.style.display = 'none'))
  handleNotBannedTopics((tr) => {
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
    }
  })
}

const filter = storage.getFilter() ?? ''
const input = ref(filter)
const inputRef = ref(null)
watch(input, (filter) => {
  applyFilter(filter)
  storage.putFilter(filter)
})

applyFilter(filter)

hotkeys.register('KeyF', 'Focus on search line', { ctrlKey: true }, () => inputRef.value.focus())
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

.filter-input {
  width: calc(100% - 20px);
  padding: 8px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
}
</style>
