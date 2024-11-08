<script setup lang="ts">
import {ref, watchEffect} from 'vue'
import { profilePics } from '@/services/clients/babepedia'
import * as tracker from '@/services/clients/tracker'
import { normalizeImages } from '@/services/parsers/image'
import * as global from '@/services/store/global'

import type { ImageNode } from '@/services/dom/topic'
import type { NormalizedImageNode } from '@/services/parsers/image'
import {useProfiles} from "@/composables/useProfiles";
import {parseNames} from "@/services/parsers/name";
import {parseTitle} from "@/services/parsers/title";

const props = defineProps<{
  topic: string
  text: string
  imageNodes: ImageNode[]
}>()

const [topic, ...comments] = props.imageNodes
const topicImages = normalizeImages(topic)
const commentImages = normalizeImages(comments)

const topicPackImages = topicImages.filter(n => n.imageLinks.some(i => i.header))
const commentPackImages = commentImages.filter(n => n.imageLinks.some(i => i.header))

// init first mode
global.imageLinks.value = topicImages.flatMap(ti => ti.imageLinks)
global.viewMode.value = 'images'

function onImages(node: NormalizedImageNode): void {
  global.imageLinks.value = node.imageLinks
  global.viewMode.value = 'images'
}

function onPack(node: NormalizedImageNode): void {
  global.pack.value = node.imageLinks
  global.viewMode.value = 'pack'
}

const filesIsLoaded = ref<boolean>(false)
async function onFiles(): Promise<void> {
  if (!filesIsLoaded.value) {
    global.files.value = await tracker.files(props.topic)
  }

  filesIsLoaded.value = true
  global.viewMode.value = 'files'
}

async function onBabepedia(babeName: string): Promise<void> {
  global.viewMode.value = 'images'

  // splice for UI/UX
  global.imageLinks.value.splice(0)
  global.imageLinks.value = await profilePics(babeName)
}

const {mainNames} = useProfiles(props.text)
</script>

<template>
  <h5>View mode</h5>
  <ul class="nav flex-column">
    <li class="nav-item">
      Images
      <ul class="nav flex-column">
        <li class="nav-item" v-for="(node, key) in topicImages" :key="key">
          <a href="#" @click.prevent.stop="onImages(node)">{{ node.header }}</a>
          [{{ node.imageLinks.length }}]
        </li>
      </ul>
    </li>
    <li class="nav-item" v-if="commentImages.length">
      Images (comments)
      <ul class="nav flex-column">
        <li class="nav-item" v-for="(node, key) in commentImages" :key="key">
          <a href="#" @click.prevent.stop="onImages(node)">{{ node.header }}</a>
          [{{ node.imageLinks.length }}]
        </li>
      </ul>
    </li>
    <li class="nav-item" v-if="topicPackImages.length">
      Pack
      <ul class="nav flex-column">
        <li class="nav-item" v-for="(node, key) in topicPackImages" :key="key">
          <a href="#" @click.prevent.stop="onPack(node)">{{ node.header }}</a>
          [{{ node.imageLinks.length }}]
        </li>
      </ul>
    </li>
    <li class="nav-item" v-if="commentPackImages.length">
      Pack (comments)
      <ul class="nav flex-column">
        <li class="nav-item" v-for="(node, key) in commentPackImages" :key="key">
          <a href="#" @click.prevent.stop="onPack(node)">{{ node.header }}</a>
          [{{ node.imageLinks.length }}]
        </li>
      </ul>
    </li>
    <li class="nav-item">
      Files
      <ul class="nav flex-column">
        <li class="nav-item">
          <a href="#" @click.prevent.stop="onFiles">Show files</a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      Babepedia
      <ul class="nav flex-column">
        <li class="nav-item" v-for="(babeName, key) in mainNames" :key="key">
          <a href="#" @click.prevent.stop="onBabepedia(babeName)">{{ babeName }}</a>
        </li>
      </ul>
    </li>
  </ul>
  <br />
</template>

<style scoped lang="scss">
</style>
