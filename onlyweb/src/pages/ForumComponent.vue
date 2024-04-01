<script setup>
import { ref, watch } from 'vue'
import { parseText } from '../services/parseText.js'
import _ from 'lodash'
import store from 'store'
import escapeStringRegexp from 'escape-string-regexp'

const handleAllTopics = (fn) =>
  document.querySelectorAll('.forum tr:has(> td.tt)').forEach((el) => fn(el))
const handleBannedTopics = (fn) => {
  document.querySelectorAll('.forum tr.fade-out:has(> td.tt)').forEach((el) => fn(el))
}
const handleNotBannedTopics = (fn) => {
  document.querySelectorAll('.forum tr:not(.fade-out):has(> td.tt)').forEach((el) => fn(el))
}

// logic
// 0. Open in new tab
// 1. fade banned
// 2. "redify" banned
// 3. apply filter (from state)
// 3.1. Hide faded and non-matched
// 3.2. "yellowfy" matched part
// 3.3. If filter empty, default state

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

  const textElement = tr.querySelector('.tt-text')
  const raw = textElement.textContent
  const words = bannedWords(raw)
  if (words.length) {
    const escaped = words.map((w) => escapeStringRegexp(w)).join('|')
    textElement.innerHTML = raw.replaceAll(
      // The last part of regex is a hack for genres that contain `)` at the end
      new RegExp('\\b(' + escaped + ')(?:\\\\b|[^\\w])', 'gi'),
      '<span class="banned">$1</span>'
    )
    tr.classList.add('fade-out')
  }
})

// Apply filter
const applyFilter = (filter) => {
  handleAllTopics((tr) => (tr.style.display = ''))
  handleNotBannedTopics(
    (tr) => (tr.querySelector('.tt-text').innerHTML = tr.querySelector('.tt-text').textContent)
  )
  if (!filter) {
    return
  }

  handleBannedTopics((tr) => (tr.style.display = 'none'))
  handleNotBannedTopics((tr) => {
    const raw = tr.querySelector('.tt-text').textContent
    const words = filter
      .split(';')
      .map((w) => w.trim())
      .filter(Boolean)
    const every = words.every((w) => raw.toLowerCase().includes(w.toLowerCase()))
    if (every) {
      const escaped = words.map((w) => escapeStringRegexp(w)).join('|')
      tr.querySelector('.tt-text').innerHTML = raw.replaceAll(
        new RegExp('(' + escaped + ')', 'gi'),
        '<span class="filter">$1</span>'
      )
    } else {
      tr.style.display = 'none'
    }
  })
}

const filter = store.get('filter') ?? ''
const input = ref(filter)
const inputRef = ref(null)
watch(input, (filter) => {
  applyFilter(filter)
  store.set('filter', filter)
})

applyFilter(filter)

window.addEventListener('keydown', (e) => {
  if (e.ctrlKey && e.key === 'f') {
    e.preventDefault()
    inputRef.value.focus()
  }
})
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
  z-index: 999;
}

.filter-input {
  width: calc(100% - 20px);
  padding: 8px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
}
</style>
