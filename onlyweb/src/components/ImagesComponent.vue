<script setup>
import { BCarousel, BCarouselSlide } from 'bootstrap-vue'
import { onDeactivated, ref } from 'vue'
import pLimit from 'p-limit'
import Deduction from '../services/deductions/deduction'

const MAX_CONCURRENCY = 3

const props = defineProps({
  images: Array
})
const carousel = ref(null)
const imagesRef = ref([])

const limit = pLimit(MAX_CONCURRENCY)
for (const { title, href } of props.images) {
  limit(async () => {
    if (Deduction.support(title, href)) {
      const link = await Deduction.do(title, href)
      imagesRef.value.push(link)
    } else {
      imagesRef.value.push(title)
    }
  })
}

const handler = (e) => {
  switch (e.which) {
    case 37: // left
      carousel.value.prev()
      return false
    case 39: // right
      carousel.value.next()
      return false
  }
}

window.addEventListener('keydown', handler)

onDeactivated(() => {
  window.removeEventListener('keydown', handler)
})
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
    <b-carousel-slide v-for="link in imagesRef" :key="link" :img-src="link"></b-carousel-slide>
  </b-carousel>
</template>

<style scoped></style>
