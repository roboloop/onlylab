<script setup>
import { ref, watch } from 'vue'
import { parseDom } from './../services/parseDom'
import LeftSideComponent from '../components/LeftSideComponent.vue'
import RightSideComponent from '../components/RightSideComponent.vue'
import ImagesComponent from '../components/ImagesComponent.vue'
import hotkeys from '../services/hotkeys'
import links from '../services/links'
import { parse } from '../services/parsers/parser.js'

let { raw, topic, forums, size, createdAt, seeds, duration, downloadLink, images } = parseDom(
  window.document
)

const { title } = parse(raw)

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

const enableOnOpen = !!import.meta.env.VITE_ENABLE_ON_OPEN
const show = ref(enableOnOpen)
const imagesRef = ref(null)
const rightSidebarRef = ref(null)
const onReload = () => {
  imagesRef.value.reloadImages()
  rightSidebarRef.value.reloadProfile(true)
}

document.body.style.overflow = enableOnOpen ? 'hidden' : 'auto'
watch(show, (newVal) => {
  document.body.style.overflow = newVal ? 'hidden' : 'auto'
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
              @exit="show = false"
              @reload="onReload"
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

            <ImagesComponent :images="images" ref="imagesRef"></ImagesComponent>
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
