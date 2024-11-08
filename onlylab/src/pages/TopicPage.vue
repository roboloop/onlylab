<script setup lang="ts">
import {ref, watch} from 'vue'
import CenterSideComponent from '@/components/layout/CenterSideComponent.vue'
import LeftSideComponent from '@/components/layout/LeftSideComponent.vue'
import RightSideComponent from '@/components/layout/RightSideComponent.vue'
import NavbarComponent from '@/components/NavbarComponent.vue'
import {BContainer} from 'bootstrap-vue-next'
// import { dom } from '../services/dom/dom'
// import LeftSideComponent from '../components/LeftSideComponent.vue'
// import RightSideComponent from '../components/RightSideComponent.vue'
// import ImagesComponent from '../components/ImagesComponent.vue'
// import FilesComponent from '../components/FilesComponent.vue'
// import ScreenlistsComponent from '../components/ScreenlistsComponent.vue'
// import hotkeys from '../services/hotkeys'
// import { parse } from '../services/parsers/parser.js'
// import storage from '../services/storage.js'
// import tracker from '@/services/clients/tracker.js'
// import image from '@/services/parsers/image.js'
// import { useHotkey } from '@/composables/useHotkey'
import {useHotkeys} from '@/composables/useHotkeys'
import {parseTopic} from '@/services/dom/topic'

const {
  createdAt,
  downloadLink,
  duration,
  forums,
  forumName,
  imageNodes,
  seeds,
  size,
  text,
  topic
} = parseTopic(document)

const fullscreen = ref(true)

const rightSidebarRef = ref(null)
const onReload = () => {
  console.log('onReload')
  // flattenImages.flatMap(p => p.images).forEach(({ title }) => storage.removeImg(title))
  // showingImages.value.splice(0)
  // showingImages.value.push(...(flattenImages?.[0]?.images ?? []))
  //
  // rightSidebarRef.value.reloadProfile(true)
}

document.body.style.overflow = fullscreen.value ? 'hidden' : 'auto'
watch(fullscreen, newVal => {
  document.body.style.overflow = newVal ? 'hidden' : 'auto'
})

const {registerHotkey} = useHotkeys()
registerHotkey({mac: 'control+A', win: 'alt+A'}, 'Open/Close OnlyLab', () => (fullscreen.value = !fullscreen.value))
registerHotkey({mac: 'control+R', win: 'alt+R'}, 'Reload topic', () => onReload())
</script>

<template>
  <div class="overlay-container" v-show="fullscreen">
    <NavbarComponent
      :text="text"
      @reload="onReload"
      @exit="fullscreen = false"
    />
    <BContainer fluid>
      <div class="row">
        <div class="col-sm-2 mt-1">
          <Suspense>
            <LeftSideComponent
              :text="text"
              :topic="topic"
              :forumName="forumName"
              :downloadLink="downloadLink"
              :createdAt="createdAt"
              :seeds="seeds"
              :duration="duration"
              :size="size"
              :imageNodes="imageNodes"/>
          </Suspense>
        </div>
        <div class="col-sm-8">
          <CenterSideComponent/>
        </div>

        <div class="col-sm-2 mt-1">
          <RightSideComponent :text="text" :forums="forums" ref="rightSidebarRef"></RightSideComponent>
        </div>
      </div>
    </BContainer>
  </div>
</template>

<style scoped lang="scss">
.overlay-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  //width: 100vw;
  //height: 100vh;
  background-color: ghostwhite;
  z-index: 99;
  overflow-y: auto;
  overscroll-behavior: none;
  border: none;
}
</style>
