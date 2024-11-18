<script setup lang="ts">
import { BListGroup } from 'bootstrap-vue-next'
import { storeToRefs } from 'pinia'
import { computed, ref, watch } from 'vue'
import ListGroupItem from '@/components/parts/ListGroupItem.vue'
import { useHighlight } from '@/composables/useHighlight'
import { useScrollIntoView } from '@/composables/useScrollIntoView'
import { profilePics } from '@/services/clients/babepedia'
import * as tracker from '@/services/clients/tracker'
import { injectId } from '@/services/dom/injector'
import { useImageStore } from '@/stores/image'
import { useProfileStore } from '@/stores/profile'
import { useViewModeStore } from '@/stores/viewMode'
import { useQueries, useQuery } from '@tanstack/vue-query'

import type { ImageLink } from '@/services/dom/topic'
import type { NormalizedImageNode } from '@/services/parsers/image'

const props = defineProps<{
  topic: string
  text: string
}>()

const { selectImageMode, selectPackMode, selectFileMode } = useViewModeStore()
const { mainNames } = storeToRefs(useProfileStore())
const { topicImages, commentImages, topicPackImages, commentPackImages } = useImageStore()
onImages(topicImages) // init

function onImages(node: NormalizedImageNode[]): void {
  selectImageMode(node.flatMap(n => n.imageLinks))
}

function onPack(node: NormalizedImageNode[]): void {
  selectPackMode(node.flatMap(n => n.imageLinks))
}

const enableFetchFiles = ref<boolean>(false)
const { data: files, isFetched: filesAreFetched } = useQuery({
  queryKey: ['files', props.topic],
  queryFn: async () => await tracker.files(props.topic),
  placeholderData: [],
  enabled: enableFetchFiles,
  staleTime: Infinity,
})

async function onFiles(): Promise<void> {
  enableFetchFiles.value = true
  watch(files, newFiles => typeof newFiles !== 'undefined' && selectFileMode(newFiles), {
    immediate: filesAreFetched.value,
  })
}

const enableFetchBabepedia = ref<boolean>(false)
const queries = computed(() => {
  return mainNames.value.map(([mainName, babeName]) => {
    return {
      queryKey: ['babename', mainName, babeName],
      queryFn: async () => await profilePics(babeName ?? mainName),
      placeholderData: [],
      enabled: enableFetchBabepedia,
      staleTime: Infinity,
    }
  })
})
const query = useQueries({ queries })
const babepediaResult = computed(() => query.value.map(r => ({ isFetched: r.isFetched, data: r.data })))

function onBabepedia(key: number): void {
  enableFetchBabepedia.value = true
  watch(
    () => babepediaResult.value[key].isFetched,
    () => selectImageMode(babepediaResult.value[key].data?.map((i): ImageLink => ({ title: i })) ?? []),
    {
      immediate: babepediaResult.value[key].isFetched,
    },
  )
}

function totalImageLinks(node: NormalizedImageNode[]): number {
  return node.reduce((acc, n) => acc + n.imageLinks.length, 0)
}

const { vHighlight } = useHighlight('active')
const { vScrollIntoView } = useScrollIntoView(document.querySelector(injectId)!)
</script>

<template>
  <h5>View mode</h5>
  <BListGroup flush>
    <!-- Topic images   -->
    <ListGroupItem
      text="All images"
      :badge="totalImageLinks(topicImages)"
      class="list-group-item-dark"
      @click="onImages(topicImages)"
      v-highlight.init
      v-scroll-into-view />
    <template v-for="(node, key) in topicImages" :key="key">
      <ListGroupItem
        :text="node.header"
        :badge="totalImageLinks([node])"
        @click="onImages([node])"
        v-highlight
        v-scroll-into-view />
    </template>

    <!-- Comments images -->
    <ListGroupItem
      v-if="commentImages.length"
      text="All Images (comments)"
      :badge="totalImageLinks(commentImages)"
      class="list-group-item-dark"
      @click="onImages(commentImages)"
      v-highlight
      v-scroll-into-view />
    <template v-for="(node, key) in commentImages" :key="key">
      <ListGroupItem
        :text="node.header"
        :badge="totalImageLinks([node])"
        @click="onImages([node])"
        v-highlight
        v-scroll-into-view />
    </template>

    <!-- Pack -->
    <ListGroupItem
      v-if="topicPackImages.length"
      text="Pack"
      :badge="totalImageLinks(topicPackImages)"
      class="list-group-item-dark"
      @click="onPack(topicPackImages)"
      v-highlight
      v-scroll-into-view />
    <template v-for="(node, key) in topicPackImages" :key="key">
      <ListGroupItem
        :text="node.header"
        :badge="totalImageLinks([node])"
        @click="onPack([node])"
        v-highlight
        v-scroll-into-view />
    </template>

    <!-- Pack (comments) -->
    <ListGroupItem
      v-if="commentPackImages.length"
      text="Pack (comments)"
      :badge="totalImageLinks(commentPackImages)"
      class="list-group-item-dark"
      @click="onPack(commentPackImages)"
      v-highlight
      v-scroll-into-view />
    <template v-for="(node, key) in commentPackImages" :key="key">
      <ListGroupItem
        :text="node.header"
        :badge="totalImageLinks([node])"
        @click="onPack([node])"
        v-highlight
        v-scroll-into-view />
    </template>

    <!-- Babepedia. TODO: clicking on the header should lead you to the first actress -->
    <ListGroupItem v-if="mainNames.length" text="Babepedia" class="list-group-item-dark" />
    <template v-for="([mainName, babeName], key) in mainNames" :key="key">
      <ListGroupItem
        :text="mainName + (babeName && babeName !== mainName ? ` (${babeName})` : '')"
        :badge="babepediaResult[key].isFetched ? babepediaResult[key].data?.length : undefined"
        @click="onBabepedia(key)"
        v-highlight
        v-scroll-into-view />
    </template>

    <!-- Files -->
    <ListGroupItem
      text="Show files"
      :badge="filesAreFetched && files ? files.length : undefined"
      class="list-group-item-dark"
      @click="onFiles"
      v-highlight
      v-scroll-into-view />
  </BListGroup>
  <br />
</template>

<style scoped lang="scss">
.fit-icon {
  color: rgba(0, 0, 0, 0.65);
  &:hover,
  &:focus {
    color: rgba(0, 0, 0, 0.8);
  }
}

// Override only for current list
.list-group :deep(.tooltip-inner) {
  max-width: 384px;
}

.list-group {
  :deep(.list-group-item) {
    padding: 0.25em 0.25em;
  }
}
</style>
