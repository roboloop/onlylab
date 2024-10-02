<script setup>
import { ref, watch } from 'vue'
import { dom } from '../services/dom/dom'
import LeftSideComponent from '../components/LeftSideComponent.vue'
import RightSideComponent from '../components/RightSideComponent.vue'
import ImagesComponent from '../components/ImagesComponent.vue'
import FilesComponent from '../components/FilesComponent.vue'
import hotkeys from '../services/hotkeys'
import { parse } from '../services/parsers/parser.js'
import storage from '../services/storage.js'
import tracker from '@/services/clients/tracker.js'
import image from '@/services/parsers/image.js'

let { raw, topic, forums, size, createdAt, seeds, duration, downloadLink, images } = dom(
  window.document
)
const { title } = parse(raw)

const enableOnOpen = !!import.meta.env.VITE_ENABLE_ON_OPEN
const show = ref(enableOnOpen)

const showImages = ref(true)
const showFiles = ref(false)

const flattenImages = image.normalize(images)
const showingImages = ref([])
showingImages.value.push(...(flattenImages?.[0]?.images ?? []))
const onImages = (id) => {
  const imagesToShow = flattenImages.find((i) => i.id === id)?.images ?? []
  showingImages.value.splice(0)
  showingImages.value.push(...imagesToShow)
}

const files = ref([])
const onFiles = async () => {
  files.value = await tracker.files(topic)
}

const rightSidebarRef = ref(null)
const onReload = () => {
  flattenImages.flatMap((p) => p.images).forEach(({ title }) => storage.removeImg(title))
  showingImages.value.splice(0)
  showingImages.value.push(...(flattenImages?.[0]?.images ?? []))

  rightSidebarRef.value.reloadProfile(true)
}

document.body.style.overflow = enableOnOpen ? 'hidden' : 'auto'
watch(show, (newVal) => {
  document.body.style.overflow = newVal ? 'hidden' : 'auto'
})

hotkeys.register('KeyA', 'Open/Close OnlyWeb', { ctrlKey: true }, () => (show.value = !show.value))
hotkeys.register('KeyR', 'Reload topic', { ctrlKey: true }, () => onReload())
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
              :images="flattenImages"
              @exit="show = false"
              @reload="onReload"
              @images="onImages"
              @files="onFiles"
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

            <ImagesComponent :images="showingImages" v-if="showImages"></ImagesComponent>
            <FilesComponent :files="files" v-if="showFiles"></FilesComponent>
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
