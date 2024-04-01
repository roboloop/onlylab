<script setup>
import { ref, watch } from 'vue'
import { parseText } from '../services/parseText.js'
import _ from 'lodash'
import store from 'store'

const bannedGenres = import.meta.env.VITE_BANNED_GENRES.split(',')
const bannedStudious = import.meta.env.VITE_BANNED_STUDIOUS.split(',')

const trs = document.querySelectorAll('.forum tr:has(> td.tt)')
for (const tr of trs) {
  const textElement = tr.querySelector('.tt-text')
  const raw = textElement.textContent
  const { title, genres, studious, quality, year } = parseText(raw)

  const commonGenres = _.intersection(
    genres.map((item) => item.toLowerCase()),
    bannedGenres.map((item) => item.toLowerCase())
  )

  const commonStudious = _.intersection(
    studious.map((item) => item.toLowerCase()),
    bannedStudious.map((item) => item.toLowerCase())
  )

  if (commonGenres.length || commonStudious.length) {
    let highlighted = raw
    for (const word of [...commonGenres, ...commonStudious]) {
      let regex = new RegExp('\\b' + word + '\\b', 'gi')
      highlighted = highlighted.replace(regex, '<span class="banned">' + word + '</span>')
    }

    textElement.innerHTML = highlighted
    tr.classList.add('fade-out')
  }
}

const input = ref('')
const inputRef = ref(null)
watch(input, (value) => {
  const trs = document.querySelectorAll('.forum tr:has(> td.tt)')
  for (const tr of trs) {
    const textElement = tr.querySelector('.tt-text')
    console.log('textElement.innerHTML', textElement.innerHTML)
    const raw = textElement.textContent.replace(/<span class="filter">([^<]+?)<\/span>/, '$1')

    const includes = raw.toLowerCase().includes(value.toLowerCase())
    if (!includes) {
      tr.style.display = 'none'
      continue
    }
    tr.style.display = ''

    let regex = new RegExp('(' + value + ')', 'gi')
    textElement.innerHTML = raw.replace(regex, '<span class="filter">' + '$1' + '</span>')
  }
  store.set('filter', value)
})

window.addEventListener('keydown', (e) => {
  if (e.ctrlKey && e.key === 'f') {
    e.preventDefault()
    inputRef.value.focus()
  }
})
// todo:
// open links without cmd
// save filter when opening a tab
// filter selections, including quality filter
// notify on a tab if all images are loaded
// option to completely hide banned images
// display duration on the side
// display number of seeds on the side
// issue with displaying Bootstrap styles on the entire page
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
