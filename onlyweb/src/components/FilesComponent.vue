<script setup>
import { BTable, BSpinner } from 'bootstrap-vue'
import { computed, defineProps } from 'vue'
import { filesize } from 'filesize'
import _ from 'lodash'

const props = defineProps({
  files: Array,
  isBusy: Boolean
})

const fields = [
  { key: 'name', sortable: true },
  { key: 'size', sortable: true }
]

const statistic = computed(() => _.countBy(props.files, ({ name }) => name.split('.').at(-1) ?? ''))
const totalSize = computed(() => props.files.reduce((acc, { size }) => acc + size, 0))
</script>

<template>
  <b-table
    :items="files"
    :fields="fields"
    :busy="isBusy"
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
  >
    <template #cell(size)="{ item }">
      {{ filesize(item.size, { standard: 'jedec' }) }}
    </template>

    <template #head()="data">
      <span class="text-light">{{ data.label }}</span>
    </template>

    <template #foot(name)>
      <!-- TODO: not json, good statistic if needed -->
      <span class="text-light">Total: {{ statistic }}</span>
    </template>

    <template #foot(size)>
      <span class="text-light">{{ filesize(totalSize, { standard: 'jedec' }) }}</span>
    </template>

    <template #table-busy>
      <div class="text-center text-dark my-2">
        <b-spinner class="align-middle"></b-spinner>
      </div>
    </template>
  </b-table>
</template>

<style scoped lang="scss"></style>
