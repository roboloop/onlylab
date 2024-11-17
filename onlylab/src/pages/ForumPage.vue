<script setup lang="ts">
import BiGear from '~icons/bi/gear?width=1.75em&height=1.75em'
import BiQuestionCircle from '~icons/bi/question-circle?width=1.75em&height=1.75em'
import { BFormCheckbox, BFormInput, BNavbar, BNavbarNav, BNavItem, useModal } from 'bootstrap-vue-next'
import { onMounted, ref, useTemplateRef } from 'vue'
import InputWrapper from '@/components/parts/InputWrapper.vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { addPills, applyFilter } from '@/services/dom/forum'
import { getForumState, putForumState } from '@/services/store/state'

import type { FilterStat } from '@/services/dom/forum'
import type { ForumState } from '@/services/store/state'

const filter = ref<string>('')
const filterRef = useTemplateRef<typeof BFormInput>('filterRef')
const hideIgnored = ref<boolean>(true)
const stat = ref<FilterStat | null>(null)

async function reapplyFilter() {
  const forum: ForumState = {
    filter: filter.value,
    hideIgnored: hideIgnored.value,
  }
  stat.value = await applyFilter(document, forum)

  await putForumState(forum)
}

onMounted(async () => {
  const forum = await getForumState()
  filter.value = forum.filter
  hideIgnored.value = forum.hideIgnored

  stat.value = await applyFilter(document, forum)
  await addPills(document)
})

const { show: showHelp } = useModal('help')
const { show: showSettings } = useModal('settings')

const { registerFocusOnSearch, registerPreviousPage, registerNextPage } = useHotkeys()
registerFocusOnSearch(() => filterRef.value?.focus())
registerPreviousPage(() => (document.querySelector('.bottom_info .nav a:nth-child(2)') as HTMLAnchorElement)?.click())
registerNextPage(() => (document.querySelector('.bottom_info .nav a:last-child') as HTMLAnchorElement)?.click())
</script>

<template>
  <BNavbar fixed="bottom" class="bg-dark text-white">
    <BFormCheckbox v-model="hideIgnored" @change="reapplyFilter" class="me-2">Hide ignored</BFormCheckbox>
    <InputWrapper :content="stat ? `Found: ${stat.found}. Ignored: ${stat.ignored}. Total: ${stat.total}.` : ''">
      <BFormInput type="text" v-model="filter" placeholder="Enter filter..." ref="filterRef" @input="reapplyFilter" />
    </InputWrapper>
    <BNavbarNav class="ms-auto mb-3 mb-lg-0">
      <BNavItem title="Show help" linkClass="text-white" @click.stop.prevent="showHelp">
        <BiQuestionCircle />
      </BNavItem>
      <BNavItem title="Show settings" linkClass="text-white" @click.stop.prevent="showSettings">
        <BiGear />
      </BNavItem>
    </BNavbarNav>
  </BNavbar>
</template>

<style scoped lang="scss"></style>
