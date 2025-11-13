<script setup lang="ts">
import { BFormInput, BTable } from 'bootstrap-vue-next'
import { filesize } from 'filesize'
import _ from 'lodash'
import { computed, defineProps, onMounted, toRef, watch } from 'vue'
import InputWrapper from '@/components/parts/InputWrapper.vue'
import { useFilter } from '@/composables/useFilter'
import { getTopicState, putTopicState } from '@/services/store/state'

import type { File } from '@/services/clients/tracker'
import type { TopicState } from '@/services/store/state'

const props = defineProps<{
  files: File[]
}>()

const fields = [
  { key: 'name', sortable: true },
  { key: 'size', sortable: true },
]

const {
  filter,
  filteredItems,
  totalComputed: [totalSize],
} = useFilter(toRef(props, 'files'), 'name', ['size'])

const statistic = computed(() =>
  Object.entries(_.countBy(filteredItems.value, ({ name }) => name.split('.').at(-1) ?? ''))
    .map(([key, value]) => `${key}: ${value}.`)
    .join(' '),
)

onMounted(async () => (filter.value = (await getTopicState()).filter))
watch(filter, async (val: string) => {
  const topic: TopicState = {
    filter: val,
  }
  await putTopicState(topic)
})
</script>

<template>
  <InputWrapper :content="`Found: ${filteredItems.length}. Total: ${files.length}.`" class="mb-2">
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
    responsive
    striped
    hover
    show-empty>
    <template #cell(name)="{ item }">
      <div v-html="item.name"></div>
    </template>

    <template #cell(size)="{ item }">
      {{ filesize(item.size, { standard: 'jedec' }) }}
    </template>

    <template #foot(name)>
      <span class="text-light">{{ statistic }}</span>
    </template>

    <template #foot(size)>
      <span class="text-light">{{ filesize(totalSize!, { standard: 'jedec' }) }}</span>
    </template>
  </BTable>
</template>

<style scoped lang="scss"></style>
