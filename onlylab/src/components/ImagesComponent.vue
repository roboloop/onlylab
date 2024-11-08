<script setup lang="ts">
import { BCarousel, BCarouselSlide, type BvCarouselEvent } from 'bootstrap-vue-next'
import {ref, toRef, watchEffect} from 'vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { usePagination } from '@/composables/usePagination'
import { useReTitle } from '@/composables/useReTitle'
import { fastpic } from '@/services/host/fastpic'
import { imageLink } from '@/services/host/host'
import { putImage } from '@/services/store/images'

import type { ImageLink } from '@/services/dom/topic'

interface Link {
  link: string
  header?: string
}

const props = defineProps<{
  imageLinks: ImageLink[] | string[]
}>()

const links = ref<Link[]>([])

const { reset: resetLoaded, inc: incLoaded } = useReTitle(document.title, toRef(props, 'imageLinks'))
const { reset: resetPagination, addCall, nextPageIfNeeded } = usePagination()

async function loadImages() {
  resetLoaded()
  resetPagination()
  links.value.splice(0)

  for (const i of props.imageLinks) {
    const { header, title, href } = typeof i === 'string' ? { header: '', title: i, href: '' } : i

    addCall(async () => {
      const link = await imageLink(title, href)
      links.value.push({ link, header })
    })
  }
}

function onSlide(event: BvCarouselEvent): void{
  // TODO:
  const link = links.value[event.to]?.link
  if (link && dirtyImages.has(link)) {
    // dirty hack that needs to determine the next or prev slide
    event.to >= currentSlide.value ? carouselRef.value?.next() : carouselRef.value?.prev()
    console.log(`slide ${event.to} with image link ${link} was skipped`)
  }
}

function onSlid(event: BvCarouselEvent): void{
  nextPageIfNeeded(event.to)
}

const dirtyImages = new Set()
const onLoad = (e: Event, index: number, link: string) => {
  incLoaded()

  const constraint = 200
  if (
    (e.target as HTMLImageElement).naturalWidth < constraint ||
    (e.target as HTMLImageElement).naturalHeight < constraint
  ) {
    dirtyImages.add(link)
    // if it is the first image
    if (index === currentSlide.value) {
      onSlide(index)
    }
  }
}

const onError = async (e: Event, index: number, link: string) => {
  // nasty hack for fixing nasty fastpic
  if (fastpic.support(link)) {
    const result = await fastpic.link(link, link)
    links.value[index].link = result
    await putImage(link, result)
  }
}

const currentSlide = ref(0)
watchEffect(async () => {
  currentSlide.value = 0
  await loadImages()
})

const carouselRef = ref<InstanceType<typeof BCarousel> | null>(null)
const { registerHotkey } = useHotkeys()
registerHotkey({ mac: 'left', win: 'left' }, 'Prev image', () => {
  carouselRef.value?.prev()
})
registerHotkey({ mac: 'right', win: 'right' }, 'Next image', () => {
  carouselRef.value?.next()
})
</script>

<template>
  <BCarousel
    indicators
    controls
    label-indicators="_"
    :interval="0"
    no-animation
    label-next=""
    label-prev=""
    ref="carouselRef"
    @slide="onSlide"
    @slid="onSlid"
    v-if="imageLinks.length"
    v-model="currentSlide">
    <BCarouselSlide v-for="({ link, header }, index) in links" :key="index">
      <span class="p-1 bg-dark rounded">{{ `${index + 1} / ${links.length}` }}</span>
      <template #img>
        <i>{{ header }}</i>
        <img
          class="d-block img-fluid w-100"
          :src="link"
          alt=""
          @load="e => onLoad(e, index, link)"
          @error="e => onError(e, index, link)" />
      </template>
    </BCarouselSlide>
  </BCarousel>
  <div class="centred" v-else>No images</div>
</template>

<style scoped lang="scss">
.centred {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 50px;
  height: 50vh;
  color: #bbb;
}
</style>
