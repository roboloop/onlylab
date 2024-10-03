<script setup>
import { BTable, BModal } from 'bootstrap-vue'
import { computed, defineProps, ref } from 'vue'
import { filesize } from 'filesize'
import size from '@/services/parsers/size.js'
import screenlist from '@/services/parsers/screenlist.js'
import Deduction from '@/services/deductions/deduction.js'

const props = defineProps({
  images: Array
})

const normalized = computed(() => {
  return props.images.map((i) => {
    const parsed = screenlist.parse(i.header)
    console.log('parsed.size', parsed.size)
    const res = {
      ...i,
      ...parsed,
      size: parsed.size ? size.fromHuman(parsed.size) : null
    }
    return res
  })
})

const fields = [
  { key: 'name', sortable: true },
  { key: 'size', sortable: true },
  { key: 'quality', sortable: true },
  { key: 'length', sortable: true },
  { key: 'extra', sortable: true }
]

const onRowClicked = async (item) => {
  const { title, href } = item
  screenshotLink.value = Deduction.support(title, href) ? await Deduction.do(title, href) : title
  screenshotRef.value.show()
}
const screenshotRef = ref(null)
const screenshotLink = ref('')
</script>

<template>
  <b-table
    :items="normalized"
    :fields="fields"
    sort-by.sync="Title"
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
  >
    <template #cell(name)="{ item }">
      {{ item.name ?? item.title }}
    </template>

    <template #cell(size)="{ item }">
      <template v-if="item.size">
        {{ filesize(item.size, { standard: 'jedec' }) }}
      </template>
    </template>

    <template #head()="data">
      <span class="text-light">{{ data.label }}</span>
    </template>
  </b-table>

  <b-modal
    ref="screenshotRef"
    size="xl"
    no-fade
    static
    hide-header
    hide-footer
    hide-backdrop
    content-class="border-0"
    @hidden="() => (screenshotLink = '')"
  >
    <template #default>
      <img
        class="d-block img-fluid w-100"
        :src="screenshotLink"
        alt=""
        v-if="screenshotLink"
        @click="screenshotRef.hide()"
      />
    </template>
  </b-modal>
</template>

<style scoped lang="scss"></style>
