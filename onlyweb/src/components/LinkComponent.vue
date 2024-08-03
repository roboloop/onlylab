<script setup>
import { BBadge } from 'bootstrap-vue'
import { defineProps, ref, defineExpose } from 'vue'

const props = defineProps({
  text: String,
  handler: Function
})

const onClick = async () => {
  const result = await props.handler()

  showSuccessBadge.value = result
  showWarningBadge.value = !result

  setTimeout(() => {
    showSuccessBadge.value = false
    showWarningBadge.value = false
  }, 5000)
}
const showSuccessBadge = ref(false)
const showWarningBadge = ref(false)

defineExpose({ onClick })
</script>

<template>
  <a href="#" target="_blank" @click.prevent.stop="onClick">{{ props.text }}</a>
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
