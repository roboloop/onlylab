<script setup lang="ts">
import { BButton, BModal } from 'bootstrap-vue-next'
import { ref, useTemplateRef } from 'vue'
import { registeredHotkeys, useHotkeys } from '@/composables/useHotkeys'
import { baseId } from '@/services/dom/injector'
import { getSettings } from '@/services/store/settings'

const helpRef = useTemplateRef<typeof BModal>('helpRef')

const isShown = ref<boolean>(false)
const { mode } = await getSettings()
const { registerOpenHelp } = useHotkeys()
registerOpenHelp(() => (isShown.value ? helpRef.value?.hide() : helpRef.value?.show()))
</script>

<template>
  <BModal id="help" ref="helpRef" v-model="isShown" noFade :teleportTo="baseId" :bodyScrolling="mode === 'overlay'">
    <template #default>
      <ul>
        <li v-for="{ hotkey, desc } of registeredHotkeys" :key="hotkey" class="fs-6">{{ hotkey }} — {{ desc }}</li>
      </ul>
    </template>
    <template #header>
      <div class="w-100">Shortcuts</div>
    </template>
    <template #footer>
      <div class="w-100">
        <BButton variant="primary" size="sm" class="float-right" @click="helpRef?.hide()"> Close </BButton>
      </div>
    </template>
  </BModal>
</template>

<style scoped lang="scss"></style>
