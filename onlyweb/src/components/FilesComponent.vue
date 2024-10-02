<script setup>
import { BTable } from 'bootstrap-vue'
import { computed, defineProps } from 'vue'

const props = defineProps({
  files: Array
})

const items = computed(() => {
  return props.files.value.map((f) => ({
    name: f.name
  }))
})

const fields = [
  { key: 'name', sortable: true },
  { key: 'size', sortable: true }
]
</script>

<template>
  <b-table :items="items" :fields="fields" sort-by.sync="Name" responsive="sm"></b-table>
</template>

<style scoped lang="scss"></style>
