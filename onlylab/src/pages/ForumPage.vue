<script setup lang="ts">
import { BFormCheckbox, BFormInput } from 'bootstrap-vue-next'
import { onMounted, ref } from 'vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { applyFilter } from '@/services/dom/forum'
import { getForum, putForum } from '@/services/store/forum'

import type { FilterStat } from '@/services/dom/forum'
import type { Forum } from '@/services/store/forum'

const filter = ref<string>('')
const filterRef = ref<InstanceType<typeof BFormInput> | null>(null)
const hideIgnored = ref<boolean>(true)
const stat = ref<FilterStat | null>(null)

async function reapplyFilter() {
  const forum: Forum = {
    filter: filter.value,
    hideIgnored: hideIgnored.value,
  }
  stat.value = await applyFilter(document, forum)

  await putForum(forum)
}

onMounted(async () => {
  const forum = await getForum()
  filter.value = forum.filter
  hideIgnored.value = forum.hideIgnored

  stat.value = await applyFilter(document, forum)
})

const { registerHotkey } = useHotkeys()
registerHotkey({ mac: 'control+F', win: 'alt+F' }, 'Focus on the search line', () => filterRef.value?.focus())
registerHotkey({ mac: 'option+left', win: 'alt+left' }, 'Previous page', () => {
  ;(document.querySelector('.bottom_info .nav a:nth-child(2)') as HTMLAnchorElement)?.click()
})
registerHotkey({ mac: 'option+right', win: 'alt+right' }, 'Next page', () => {
  ;(document.querySelector('.bottom_info .nav a:last-child') as HTMLAnchorElement)?.click()
})
</script>

<template>
  <div class="fixed-bottom-bar">
    <div class="d-flex align-items-center">
      <div class="me-2">
        <BFormCheckbox v-model="hideIgnored" @change="reapplyFilter">Hide ignored</BFormCheckbox>
      </div>
      <BFormInput
        type="text"
        class="filter-input"
        v-model="filter"
        placeholder="Enter filter..."
        ref="filterRef"
        @input="reapplyFilter" />
    </div>
    <div v-if="stat">
      <span>Total: {{ stat.total }}. </span>
      <span>Ignored: {{ stat.ignored }}. </span>
      <span>Found: {{ stat.found }}. </span>
    </div>
  </div>
</template>

<style scoped lang="scss">
.fixed-bottom-bar {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background-color: #333;
  color: #fff;
  padding: 10px;
  box-sizing: border-box;
  z-index: 99;
}

.icon {
  cursor: pointer;
  width: 20px;
  height: 20px;
  filter: invert(100%);
  padding-bottom: 4px;
}

.filter-input {
  width: calc(100% - 20px);
  padding: 8px;
  border: none;
  border-radius: 5px;
  margin-right: 10px;
}
</style>
