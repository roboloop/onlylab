<script setup lang="ts">
import { BFormInput, BModal, BTable } from 'bootstrap-vue-next'
import { filesize } from 'filesize'
import { computed, defineProps, ref } from 'vue'
import { imageLink } from '@/services/host/host'
import { parseScreenlist } from '@/services/parsers/screenlist'
import { formatLength } from '@/services/utils/formatters'

import type { TableFieldRaw } from 'bootstrap-vue-next'
import type { ImageLink } from '@/services/dom/topic'

interface ScreenlistTableField {
  title: string
  href?: string
  name: string
  size: string | number
  quality: string
  length: string
  extra: string
}

const props = defineProps<{
  imageLinks: ImageLink[]
}>()

const fields = ref<TableFieldRaw<ScreenlistTableField>[]>([
  { key: 'name', sortable: true, filterByFormatted: true },
  { key: 'size', sortable: true, filterByFormatted: true },
  { key: 'quality', sortable: true, filterByFormatted: true },
  { key: 'length', sortable: true, filterByFormatted: true },
  { key: 'extra', sortable: true, filterByFormatted: true },
])

const items = computed(() => {
  return props.imageLinks.map(i => {
    const parsed = parseScreenlist(i.header)

    return {
      title: i.title,
      href: i.href,
      ...parsed,
    }
  })
})

const onRowClicked = async item => {
  const { title, href } = item
  screenshotLink.value = await imageLink(title, href)
  screenshotRef.value?.show()
}
const screenshotRef = ref(null)
const screenshotLink = ref('')

const filteredItems = ref([...items.value])
const totalSize = computed((): number =>
  filteredItems.value.filter(({ size }) => typeof size === 'number').reduce((acc, { size }) => acc + size, 0),
)

const totalLength = computed((): number =>
  filteredItems.value.filter(({ length }) => typeof length === 'number').reduce((acc, { length }) => acc + length, 0),
)

const filter = ref<string>('')
const filterRef = ref<InstanceType<typeof BFormInput> | null>(null)
function onFiltered(items /*: TableItem<Person>[]*/): void {
  filteredItems.value = items
}
</script>

<template>
  <BFormInput type="search" v-model="filter" placeholder="Enter filter..." ref="filterRef" />

  <BTable
    :items="items"
    :fields="fields"
    :filter="filter"
    sort-by.sync="Name"
    :small="true"
    sticky-header="500px"
    thead-tr-class="tr-sticky"
    head-variant="dark"
    foot-variant="dark"
    foot-clone
    no-footer-sorting
    responsive
    striped
    hover
    show-empty
    @row-clicked="onRowClicked"
    @filtered="onFiltered">
    <template #cell(name)="{ item }">
      {{ item.name ?? item.title }}
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
    size="xl"
    no-fade
    static
    hide-header
    hide-footer
    hide-backdrop
    content-class="border-0"
    teleport-to="#app"
    @hidden="() => (screenshotLink = '')">
    <template #default>
      <img
        v-if="screenshotLink"
        class="d-block img-fluid w-100"
        :src="screenshotLink"
        alt=""
        @click="screenshotRef.hide()" />
    </template>
  </BModal>
</template>

<style scoped lang="scss"></style>
