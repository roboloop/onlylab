<script setup>
import { BCarousel, BCarouselSlide } from 'bootstrap-vue'
import { ref, defineExpose } from 'vue'
import pLimit from 'p-limit'
import Deduction from '../services/deductions/deduction'

const MAX_CONCURRENCY = 4
const PER_IMAGES = 20
let current_images = PER_IMAGES

const props = defineProps({
  images: Array
})
const carousel = ref(null)
const imageLinks = ref([])

let resolveOuter
const limit = pLimit(MAX_CONCURRENCY)
const loadImages = async () => {
  for (const index in props.images) {
    const { header, title, href } = props.images[index]
    limit(async () => {
      if (Deduction.support(title, href)) {
        const link = await Deduction.do(title, href)
        imageLinks.value.push({ link, header })
      } else {
        imageLinks.value.push({ link: title, header })
      }
    })

    if (+index + 1 >= current_images) {
      const p = new Promise((resolve) => {
        resolveOuter = resolve
      })
      await Promise.all([p])
      current_images += PER_IMAGES
    }
  }
}
loadImages()

const handleSlidingEnd = (slide) => {
  if (slide >= current_images - PER_IMAGES / 2) {
    resolveOuter()
  }
}

const reloadImages = () => {
  totalLoaded = 0
  props.images.forEach(({ t }) => Deduction.clear(t))
  imageLinks.value.splice(0)

  loadImages()
}

defineExpose({ reloadImages })

const originalTitle = document.title
let totalLoaded = 0
const handleLoad = () => {
  totalLoaded++
  document.title = `[${totalLoaded}/${props.images.length}] ${originalTitle}`
}

const handler = (e) => {
  if (e.which === 37) {
    // left
    carousel.value.prev()
  }
  if (e.which === 39) {
    // right
    carousel.value.next()
  }
}
window.addEventListener('keydown', handler)
</script>

<template>
  <!-- TODO: indicator -->
  <b-carousel
    indicators
    controls
    label-indicators="_"
    :interval="0"
    no-animation
    label-next=""
    label-prev=""
    ref="carousel"
    @sliding-end="handleSlidingEnd"
  >
    <b-carousel-slide v-for="({ link, header }, index) in imageLinks" :key="link" :img-src="link">
      {{ `${index + 1} / ${images.length}` }}
      <template #img>
        <i>{{ header }}</i>
        <img class="d-block img-fluid w-100" :src="link" alt="" @load="handleLoad" />
      </template>
    </b-carousel-slide>
  </b-carousel>
</template>

<style scoped></style>
