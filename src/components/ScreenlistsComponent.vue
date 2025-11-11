<script setup lang="ts">
import { BFormInput, BModal, BTable } from 'bootstrap-vue-next'
import { filesize } from 'filesize'
import { computed, defineProps, onMounted, ref, useTemplateRef, watch } from 'vue'
import InputWrapper from '@/components/parts/InputWrapper.vue'
import { useFilter } from '@/composables/useFilter'
import { baseId } from '@/services/dom/injector'
import { imageLink } from '@/services/host/host'
import { parseScreenlist } from '@/services/parsers/screenlist'
import { getTopicState, putTopicState } from '@/services/store/state'
import { formatLength } from '@/services/utils/formatters'

import type { TableFieldRaw } from 'bootstrap-vue-next'
import type { ImageLink } from '@/services/dom/topic'
import type { ParsedScreenlist } from '@/services/parsers/screenlist'
import type { TopicState } from '@/services/store/state'

interface ScreenlistTableField extends ParsedScreenlist {
  title: string
  href?: string
  name: string
}

const props = defineProps<{
  imageLinks: ImageLink[]
}>()

const fields: TableFieldRaw<ScreenlistTableField>[] = [
  { key: 'name', sortable: true, filterByFormatted: true },
  { key: 'size', sortable: true, filterByFormatted: true },
  { key: 'quality', sortable: true, filterByFormatted: true },
  { key: 'length', sortable: true, filterByFormatted: true },
  { key: 'extra', sortable: true, filterByFormatted: true },
]

const items = computed(() => {
  return props.imageLinks.map(i => {
    const parsed = parseScreenlist(i.header)

    return {
      title: i.title,
      href: i.href,
      ...parsed,
      name: parsed.name ?? i.title,
    } as ScreenlistTableField
  })
})

async function onRowClicked(item: ScreenlistTableField) {
  const { title, href } = item
  screenshotLink.value = await imageLink(title, href)
  screenshotRef.value?.show()
}
const screenshotRef = useTemplateRef<typeof BModal>('screenshotRef')
const screenshotLink = ref('')

const {
  filter,
  filteredItems,
  totalComputed: [totalSize, totalLength],
} = useFilter(items, 'name', ['size', 'length'])
onMounted(async () => (filter.value = (await getTopicState()).filter))
watch(filter, async (val: string) => {
  const topic: TopicState = {
    filter: val,
  }
  await putTopicState(topic)
})
</script>

<template>
  <InputWrapper :content="`Found: ${filteredItems.length}. Total: ${items.length}.`" class="mb-2">
    <BFormInput type="text" v-model="filter" placeholder="Enter filter..." :debounce="100" />
  </InputWrapper>
  <BTable
    :items="filteredItems"
    :fields="fields"
    small
    sticky-header="512px"
    head-variant="dark"
    foot-variant="dark"
    foot-clone
    no-footer-sorting
    striped
    hover
    show-empty
    @row-clicked="onRowClicked">
    <template #cell(name)="{ item }">
      <div v-html="item.name"></div>
    </template>

    <template #cell(size)="{ item }">
      <template v-if="item.size">
        {{ typeof item.size === 'number' ? filesize(item.size, { standard: 'jedec' }) : item.size }}
      </template>
    </template>

    <template #cell(length)="{ item }">
      <template v-if="item.length">
        {{ typeof item.length === 'number' ? formatLength(item.length) : item.length }}
      </template>
    </template>

    <template #foot(name)> Total: </template>
    <template #foot(size)>
      {{ filesize(totalSize, { standard: 'jedec' }) }}
    </template>
    <template #foot(length)>
      {{ formatLength(totalLength) }}
    </template>
    <template #foot()>
      <span></span>
    </template>
  </BTable>

  <BModal
    ref="screenshotRef"
    no-fade
    hide-header
    hide-footer
    content-class="border-0"
    :teleport-to="baseId"
    size="xl"
    @hidden="() => (screenshotLink = '')">
    <template #default>
      <img
        v-if="screenshotLink"
        class="d-block img-fluid w-100"
        :src="screenshotLink"
        alt=""
        @click="screenshotRef?.hide()" />
    </template>
  </BModal>
</template>

<style scoped lang="scss"></style>
