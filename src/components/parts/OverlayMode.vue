<script setup lang="ts">
import { watch } from 'vue'
import { injectId } from '@/services/dom/injector'
import { getSettings } from '@/services/store/settings'

const props = withDefaults(
  defineProps<{
    show: boolean
  }>(),
  {
    show: true,
  },
)

const { mode } = await getSettings()

document.body.style.overflow = mode === 'overlay' ? 'hidden' : 'auto'
watch(
  () => props.show,
  newVal => {
    document.body.style.overflow = mode === 'overlay' && newVal ? 'hidden' : 'auto'
  },
)

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const slots = defineSlots<{
  default: void
}>()
</script>

<template>
  <div v-if="mode === 'overlay'" v-show="show" class="overlay-container">
    <slot />
  </div>
  <template v-else-if="mode === 'inject'">
    <Teleport :to="injectId">
      <div v-show="show">
        <slot />
      </div>
    </Teleport>
  </template>
</template>

<style scoped lang="scss">
.overlay-container {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #fff;
  z-index: 99;
  overflow-y: auto;
  overscroll-behavior: none;
  border: none;
}
</style>
