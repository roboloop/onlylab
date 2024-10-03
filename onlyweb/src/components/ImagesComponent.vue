<script setup>
import { BCarousel, BCarouselSlide } from 'bootstrap-vue'
import { ref, watch, onMounted, onBeforeUnmount } from 'vue'
import pLimit from 'p-limit'
import Deduction from '../services/deductions/deduction'
import hotkeys from '../services/hotkeys'

const ORIGINAL_TITLE = document.title
const MAX_CONCURRENCY = 4
const STEP_LOADED = 20
const totalLoaded = ref(0)
let limitLoaded = STEP_LOADED

const props = defineProps({
  images: Array
})
const imageLinks = ref([])

let resolveOuter
const limit = pLimit(MAX_CONCURRENCY)
const loadImages = async (images) => {
  totalLoaded.value = 0
  limitLoaded = STEP_LOADED
  imageLinks.value.splice(0)

  for (const index in images) {
    const { header, title, href } = images[index]
    limit(async () => {
      if (Deduction.support(title, href)) {
        const link = await Deduction.do(title, href)
        imageLinks.value.push({ title, link, header })
      } else {
        imageLinks.value.push({ title, link: title, header })
      }
    })

    if (+index + 1 >= limitLoaded) {
      const p = new Promise((resolve) => {
        resolveOuter = resolve
      })
      await Promise.all([p])
      limitLoaded += STEP_LOADED
    }
  }
}

const onSlidingStart = (slideIndex) => {
  const link = imageLinks.value[slideIndex]?.link
  if (link && dirtyImages.has(link)) {
    // dirty hack that needs to determine the next or prev slide
    slideIndex > currentSlide.value ? carouselRef.value.next() : carouselRef.value.prev()
    console.log(`slide ${slideIndex} with image link ${link} was skipped`)
  }
}

const onNextSlide = (slideIndex) => {
  if (slideIndex >= limitLoaded - STEP_LOADED / 2 && resolveOuter) {
    resolveOuter()
  }
}

const dirtyImages = new Set()
const onLoad = (e, link) => {
  ++totalLoaded.value

  const constraint = 200
  if (e.target.naturalWidth < constraint || e.target.naturalHeight < constraint) {
    dirtyImages.add(link)
  }
}

const currentSlide = ref(0)
watch(
  () => props.images,
  async (n) => {
    currentSlide.value = 0
    await loadImages(n)
  },
  { deep: true, immediate: true }
)
watch(
  totalLoaded,
  (n) => {
    document.title = `[${n}/${props.images.length}] ${ORIGINAL_TITLE}`
  },
  { immediate: true }
)

const carouselRef = ref(null)
onMounted(() => {
  hotkeys.register('ArrowLeft', 'Next image', {}, () => carouselRef.value.prev())
  hotkeys.register('ArrowRight', 'Prev image', {}, () => carouselRef.value.next())
})
onBeforeUnmount(() => {
  hotkeys.unregister('ArrowLeft', {})
  hotkeys.unregister('ArrowRight', {})
})
</script>

<template>
  <b-carousel
    indicators
    controls
    label-indicators="_"
    :interval="0"
    no-animation
    label-next=""
    label-prev=""
    ref="carouselRef"
    @sliding-start="onSlidingStart"
    @sliding-end="onNextSlide"
    v-if="imageLinks.length"
    v-model="currentSlide"
  >
    <b-carousel-slide v-for="({ link, header }, index) in imageLinks" :key="link" :img-src="link">
      {{ `${index + 1} / ${images.length}` }}
      <template #img>
        <i>{{ header }}</i>
        <img class="d-block img-fluid w-100" :src="link" alt="" @load="(e) => onLoad(e, link)" />
      </template>
    </b-carousel-slide>
  </b-carousel>
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
