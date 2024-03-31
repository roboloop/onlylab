<script setup>
import { ref, watch } from 'vue'
import { parseDom } from './services/parseDom'
import { parseText } from './services/parseText'
import SideComponent from './components/SideComponent.vue'
import ImagesComponent from './components/ImagesComponent.vue'
import store from 'store'
import client from './services/clients'

let { raw, size, createdAt, downloadLink, images } = parseDom(window.document)
const { title } = parseText(raw)
const show = ref(false)

window.addEventListener('keydown', (e) => {
  if (e.ctrlKey && e.key === 'a') {
    e.preventDefault()
    show.value = !show.value
  }
  if (e.ctrlKey && e.key === 'c') {
    e.preventDefault()
    store.clearAll()
  }
  if (e.ctrlKey && e.key === 'd') {
    e.preventDefault()
    const file = client.send({ url: downloadLink }).then((resp) => {
      store.set('torrent', btoa(resp))
      console.log(resp.responseText)
    })
    console.log(file)
    // window.open(downloadLink, '_blank')
  }
})

watch(show, (newVal) => {
  document.body.style.overflow = newVal ? 'hidden' : 'auto'
})
</script>

<template>
  <div class="overlay-container" v-show="show">
    <div class="overlay-content">
      <div class="container-fluid" style="">
        <div class="row">
          <div class="col-sm-2"></div>
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

          <div class="col-sm-2">
            <SideComponent
              :raw="raw"
              :downloadLink="downloadLink"
              :size="size"
              :createdAt="createdAt"
              @exit="show = false"
            ></SideComponent>
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
  z-index: 9998;
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
