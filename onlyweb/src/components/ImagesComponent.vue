<script setup>
import { BCarousel, BCarouselSlide } from 'bootstrap-vue'
import { ref, defineExpose } from 'vue'
import pLimit from 'p-limit'
import Deduction from '../services/deductions/deduction'

const MAX_CONCURRENCY = 3

const props = defineProps({
  images: Array
})
const carousel = ref(null)
const imageLinks = ref([])

const loadImages = () => {
  const limit = pLimit(MAX_CONCURRENCY)
  for (const { title, href } of props.images) {
    limit(async () => {
      if (Deduction.support(title, href)) {
        const link = await Deduction.do(title, href)
        imageLinks.value.push(link)
      } else {
        imageLinks.value.push(title)
      }
    })
  }
}
loadImages()

const reloadImages = () => {
  props.images.forEach(({ t }) => Deduction.clear(t))
  imageLinks.value.splice(0)

  loadImages()
}

defineExpose({ reloadImages })

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
    style="text-shadow: 0 0 2px #000"
    label-next=""
    label-prev=""
    ref="carousel"
  >
    <b-carousel-slide v-for="link in imageLinks" :key="link" :img-src="link"></b-carousel-slide>
  </b-carousel>
</template>

<style scoped></style>
