<script setup lang="ts">
import { BCarousel, BCarouselSlide } from 'bootstrap-vue-next'
import _ from 'lodash'
import { nextTick, ref, toRef, useTemplateRef, watchEffect } from 'vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { usePagination } from '@/composables/usePagination'
import { useReTitle } from '@/composables/useReTitle'
import { injectId } from '@/services/dom/injector'
import { fastpic } from '@/services/host/fastpic'
import { imageLink } from '@/services/host/host'
import { putImage } from '@/services/store/images'
import { getSettings } from '@/services/store/settings'

import type { BvCarouselEvent } from 'bootstrap-vue-next'
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
const {
  main: { skipSmallImages },
} = await getSettings()

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

function onSlid(event: BvCarouselEvent): void {
  nextPageIfNeeded(event.to)

  const link = links.value[event.to]?.link
  // the last condition is to prevent cycle
  if (skipSmallImages && link && dirtyImages.has(link) && totalSkipped.value < links.value.length - 1) {
    nextTick(() => {
      event.direction === 'right' ? carouselRef.value?.next() : carouselRef.value?.prev()
      totalSkipped.value++
    })
  }
}

const dirtyImages = new Set()
const totalSkipped = ref<number>(0)
const onLoad = (e: Event, index: number, link: string) => {
  incLoaded()

  const constraint = 128
  const image = e.target as HTMLImageElement
  if (skipSmallImages && (image.naturalWidth <= constraint || image.naturalHeight <= constraint)) {
    dirtyImages.add(link)
    // if we're looking at that image
    if (index === currentSlide.value) {
      carouselRef.value?.next()
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

const fitImages = ref<boolean>(false)
const elementToScroll = document.querySelector(injectId)!
function onImageClick(event: MouseEvent): void {
  fitImages.value = !fitImages.value
  if (fitImages.value) {
    elementToScroll.scrollIntoView()
    return
  }

  const image = event.target as HTMLImageElement
  const imageBounds = image.getBoundingClientRect()
  const imageClickY = event.clientY - imageBounds.top

  // Top scroll is image's top (Math.random — hack to remove white pixel above)
  // const finalTopScroll = Math.round(window.scrollY + imageBounds.top)

  const finalTopScroll = window.scrollY + elementToScroll.getBoundingClientRect().top
  const finalTopImageScroll = window.scrollY + imageBounds.top
  const finalImageHeight = (carouselRef.value!.$el.offsetWidth / image.naturalWidth) * image.naturalHeight
  const finalBottomScroll = finalTopImageScroll + finalImageHeight - window.innerHeight
  const finalTargetScroll =
    finalTopImageScroll + (imageClickY / imageBounds.height) * finalImageHeight - window.innerHeight / 2

  const finalScroll = _.clamp(finalTargetScroll, finalTopScroll, finalBottomScroll)

  // Wait until a browser renders a full size an image and scroll position becomes valid
  nextTick(() => {
    window.scrollTo({
      top: finalScroll,
      behavior: 'instant',
    })
  })
}

const currentSlide = ref<number>(0)
watchEffect(async () => {
  currentSlide.value = 0
  totalSkipped.value = 0
  await loadImages()
})

const carouselRef = useTemplateRef<typeof BCarousel>('carouselRef')
const { registerPrevImage, registerNextImage } = useHotkeys()
registerPrevImage(() => carouselRef.value?.prev())
registerNextImage(() => carouselRef.value?.next())
</script>

<template>
  <BCarousel
    v-if="imageLinks.length"
    v-model="currentSlide"
    indicators
    controls
    :interval="0"
    no-animation
    ref="carouselRef"
    background="unset"
    @slid="onSlid">
    <BCarouselSlide v-for="({ link }, index) in links" :key="index">
      <template #text>
        <span class="p-1 bg-dark rounded">{{ `${index + 1} / ${links.length}` }}</span>
      </template>
      <template #img>
        <img
          class="d-block w-100"
          :class="{ fit: fitImages }"
          :src="link"
          alt=""
          @click.prevent.stop="onImageClick"
          @load="e => onLoad(e, index, link)"
          @error="e => onError(e, index, link)" />
      </template>
    </BCarouselSlide>
  </BCarousel>
  <div class="centred" v-else>No images</div>
  <div class="text-center">{{ links?.[currentSlide]?.header }}</div>
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

.fit {
  max-width: 100%;
  max-height: 90vh;
  object-fit: contain;
}

// Allow clicking on capture area
:global(.carousel-caption) {
  pointer-events: none;
}
</style>
