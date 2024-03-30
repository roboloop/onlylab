<script setup>
import { ref, onDeactivated } from 'vue'
import { parseDom } from './services/parseDom'
import { parseText } from './services/parseText'
import SideComponent from './components/SideComponent.vue'
import ImagesComponent from './components/ImagesComponent.vue'
import store from 'store'

let { raw, size, createdAt, downloadLink, images } = parseDom(window.document)
const { title } = parseText(raw)
const show = ref(false)

const onKeydown = (e) => {
  if (e.ctrlKey && e.key === 'a') {
    e.preventDefault()
    show.value = !show.value
  }
  if (e.ctrlKey && e.key === 'c') {
    e.preventDefault()
    store.clearAll()
  }
}
window.addEventListener('keydown', onKeydown)
onDeactivated(() => {
  window.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <!--  <Banner-->
  <!--      @click="openOverlay"-->
  <!--  ></Banner>-->
  <div class="overlay" v-show="show">
    <div class="overlay-item">
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
.overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 9999;
}

.overlay-item {
  position: absolute;
  background-color: lightgray;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 100%;
  height: 100%;
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
