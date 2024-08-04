<script setup>
import { ref, watch } from 'vue'
import { parseDom } from './../services/parseDom'
import LeftSideComponent from '../components/LeftSideComponent.vue'
import RightSideComponent from '../components/RightSideComponent.vue'
import ImagesComponent from '../components/ImagesComponent.vue'
import hotkeys from '../services/hotkeys'
import links from '../services/links'
import { parse } from '../services/parsers/parser.js'
import storage from '../services/storage.js'

let {
  raw,
  topic,
  forums,
  size,
  createdAt,
  seeds,
  duration,
  downloadLink,
  topicImages,
  commentImages
} = parseDom(window.document)
const { title } = parse(raw)

const enableOnOpen = !!import.meta.env.VITE_ENABLE_ON_OPEN
const show = ref(enableOnOpen)

const images = ref([])
images.value.push(...topicImages)
const onTopicImages = () => {
  images.value.splice(0)
  images.value.push(...topicImages)
}
const onCommentImages = () => {
  images.value.splice(0)
  images.value.push(...commentImages)
}

const rightSidebarRef = ref(null)
const onReload = () => {
  ;[...topicImages, ...commentImages].forEach(({ title }) => storage.removeImg(title))
  images.value.splice(0)
  images.value.push(...topicImages)

  rightSidebarRef.value.reloadProfile(true)
}

document.body.style.overflow = enableOnOpen ? 'hidden' : 'auto'
watch(show, (newVal) => {
  document.body.style.overflow = newVal ? 'hidden' : 'auto'
})

hotkeys.register('KeyA', 'Open/Close OnlyWeb', { ctrlKey: true }, () => (show.value = !show.value))
hotkeys.register('KeyR', 'Reload topic', { ctrlKey: true }, () => onReload())
hotkeys.register('KeyB', 'Open the first babepedia link', { ctrlKey: true }, () => {
  const name = rightSidebarRef.value.profiles?.[0]?.name
  if (rightSidebarRef.value.profiles?.[0]?.name) {
    window.open(links.babepediaLink(name), '_blank')
  }
})
hotkeys.register('KeyL', 'Open the first tracker search link', { ctrlKey: true }, () => {
  const name = rightSidebarRef.value.profiles?.[0]?.name
  if (rightSidebarRef.value.profiles?.[0]?.name) {
    window.open(links.trackerSearchLink(name), '_blank')
  }
})
</script>

<template>
  <div class="overlay-container" v-show="show">
    <div class="overlay-content">
      <div class="container-fluid" style="">
        <div class="row">
          <div class="col-sm-2 mt-1">
            <LeftSideComponent
              :raw="raw"
              :topic="topic"
              :downloadLink="downloadLink"
              :createdAt="createdAt"
              :seeds="seeds"
              :duration="duration"
              :size="size"
              :totalTopicImages="topicImages.length"
              :totalCommentImages="commentImages.length"
              @exit="show = false"
              @reload="onReload"
              @topicImages="onTopicImages"
              @commentImages="onCommentImages"
            ></LeftSideComponent>
          </div>
          <div class="col-sm-8">
            <header class="topic-header">
              <template v-if="title">
                <h2>{{ title }}</h2>
                <p>{{ raw }}</p>
              </template>
              <template v-else>
                <h2>{{ raw }}</h2>
              </template>
            </header>

            <ImagesComponent :images="images"></ImagesComponent>
          </div>

          <div class="col-sm-2 mt-1">
            <RightSideComponent
              :raw="raw"
              :forums="forums"
              ref="rightSidebarRef"
            ></RightSideComponent>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped lang="scss">
.overlay-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 99;
  overflow-y: auto;
  overscroll-behavior: none;
}

.overlay-content {
  background-color: ghostwhite;
  border: none;
}

header {
  line-height: 1.5;
}

header.topic-header {
  margin: 0;
  padding: 0;
}

header.topic-header > h2 {
  margin: 0;
  padding: 0;
}
</style>
