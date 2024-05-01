<script setup>
import { BBadge } from 'bootstrap-vue'
import qbit from '../services/qbit.js'
import { defineProps, ref } from 'vue'

const props = defineProps({
  text: String,
  downloadLink: String,
  paused: Boolean
})

const onDownload = async () => {
  const today = new Date()
  const day = ('0' + today.getDate()).slice(-2)
  const month = ('0' + (today.getMonth() + 1)).slice(-2)
  const folder = day + month
  const result = await qbit.upload(props.downloadLink, folder, props.paused)

  showSuccessBadge.value = result
  showWarningBadge.value = !result

  setTimeout(() => {
    showSuccessBadge.value = false
    showWarningBadge.value = false
  }, 5000)
}
const showSuccessBadge = ref(false)
const showWarningBadge = ref(false)
</script>

<template>
  <a :href="downloadLink" target="_blank" @click.prevent.stop="onDownload">{{ props.text }}</a>
  <Transition appear>
    <b-badge variant="success" :pill="true" style="margin-left: 12px" v-if="showSuccessBadge"
      >Success</b-badge
    >
  </Transition>
  <Transition appear>
    <b-badge variant="warning" :pill="true" style="margin-left: 12px" v-if="showWarningBadge"
      >Fail</b-badge
    >
  </Transition>
</template>

<style scoped lang="scss">
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
