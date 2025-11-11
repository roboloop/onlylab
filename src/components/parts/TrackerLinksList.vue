<script setup lang="ts">
import BiBoxArrowUpRight from '~icons/bi/box-arrow-up-right?width=0.75em&height=0.75em'
import { BLink } from 'bootstrap-vue-next'
import * as links from '@/services/utils/links'

// eslint-disable-next-line @typescript-eslint/no-unused-vars
const props = defineProps<{
  entities: string[]
  name: string
  emptyMessage: string
}>()
</script>

<template>
  <h5>{{ name }}</h5>
  <span v-if="!entities.length"
    ><i>{{ emptyMessage }}</i></span
  >
  <ul class="nav flex-column">
    <li class="nav-item" v-for="entity in entities" :key="entity">
      <BLink
        icon
        :href="links.trackerSearchLink(entity)"
        target="_blank"
        rel="noreferrer"
        :title="`Search for &quot;${entity}&quot; on Tracker`">
        {{ entity }}
        <BiBoxArrowUpRight />
      </BLink>
    </li>
  </ul>
  <br />
</template>

<style scoped lang="scss"></style>
