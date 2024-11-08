<script setup lang="ts">
import { BButton, BModal } from 'bootstrap-vue-next'
import {defineComponent, h, ref} from 'vue'
import { registeredHotkeys, useHotkeys } from '@/composables/useHotkeys'

const helpRef = ref<InstanceType<typeof BModal> | null>(null)

const isShown = ref<boolean>(false)
const { registerHotkey } = useHotkeys()
registerHotkey({ mac: 'shift+/', win: 'shift+/' }, 'Open this help', () => {
  isShown.value ? helpRef.value?.hide() : helpRef.value?.show()
})

</script>

<template>
  <BModal
    id="help"
    ref="helpRef"
    noTrap
    noFade
    teleport-to="#app"
    v-model="isShown"
  >
    <template #default>
      <ul>
        <li v-for="{ hotkey, desc } of registeredHotkeys" :key="hotkey" class="fs-6">
          {{ hotkey }} — {{ desc }}
        </li>
      </ul>
    </template>
    <template #header>
      <div class="w-100">Shortcuts</div>
    </template>
    <template #footer>
      <div class="w-100">
        <BButton variant="primary" size="sm" class="float-right" @click="(helpRef as any)?.hide()"> Close </BButton>
      </div>
    </template>
  </BModal>
</template>

<style scoped lang="scss"></style>
