<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  content: string
}>()

const inputStyle = computed(() => {
  if (!props.content) {
    return {}
  }
  return {
    '--input-content': `"${props.content}"`,
  }
})

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const slots = defineSlots<{
  default: void
}>()
</script>

<template>
  <div class="input-wrapper" :style="inputStyle">
    <slot />
  </div>
</template>

<style scoped lang="scss">
.input-wrapper {
  width: 100%;
  position: relative;
  display: inline-block;

  &::after {
    content: var(--input-content);
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #212529;
    pointer-events: none;
  }
}
</style>
