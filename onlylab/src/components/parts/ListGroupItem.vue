<script setup lang="ts">
import { BBadge, BListGroupItem, vBTooltip } from 'bootstrap-vue-next'
import { onMounted, ref, useTemplateRef } from 'vue'

const props = withDefaults(
  defineProps<{
    text: string
    badge?: number
  }>(),
  {
    text: '',
    badge: undefined,
  },
)

const emit = defineEmits<{
  click: []
}>()

const textRef = useTemplateRef<HTMLElement>('textRef')
const tooltipText = ref<string>('')

onMounted(() => {
  if (
    typeof textRef?.value?.scrollHeight !== 'undefined' &&
    typeof textRef?.value?.clientHeight !== 'undefined' &&
    textRef?.value?.scrollHeight > textRef?.value?.clientHeight
  ) {
    tooltipText.value = props.text
  }
})
</script>

<template>
  <BListGroupItem
    button
    class="d-flex justify-content-between align-items-center"
    v-bind="$attrs"
    v-b-tooltip.right="tooltipText"
    @click.prevent.stop="emit('click')">
    <span class="list-group-item-text" ref="textRef">
      {{ text }}
    </span>
    <BBadge v-if="typeof badge !== 'undefined'" variant="primary" pill>{{ badge }} </BBadge>
  </BListGroupItem>
</template>

<style scoped lang="scss">
.list-group-item-text {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
  max-height: 3.2em;
  margin-right: 0.25em;
}
</style>
