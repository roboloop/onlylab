<script setup lang="ts">
import BiArrowClockwise from '~icons/bi/arrow-clockwise?width=1.75em&height=1.75em'
import BiBoxArrowUpRight from '~icons/bi/box-arrow-up-right?width=1em&height=1em'
import BiGear from '~icons/bi/gear?width=1.75em&height=1.75em'
import BiQuestionCircle from '~icons/bi/question-circle?width=1.75em&height=1.75em'
import BiXCircle from '~icons/bi/x-circle?width=1.75em&height=1.75em'
import {
  BLink,
  BNavbar,
  BNavbarBrand,
  BNavbarNav,
  BNavItem,
  useModal,
  useToastController,
  vBTooltip,
} from 'bootstrap-vue-next'
import { useHotkeys } from '@/composables/useHotkeys'
import { parseTitle } from '@/services/parsers/title'
import * as links from '@/services/utils/links'
import { useImageStore } from '@/stores/image'
import { useProfileStore } from '@/stores/profile'

const props = defineProps<{
  text: string
}>()

const title = parseTitle(props.text) || props.text
const emit = defineEmits<{
  reload: []
  exit: []
}>()

const { show: showHelp } = useModal('help')
const { show: showSettings } = useModal('settings')
const { show: showToast } = useToastController()

const { cleanCache: cleanCacheOfImageStore } = useImageStore()
const { cleanCache: cleanCacheOfProfileStore } = useProfileStore()
async function reloadTopic() {
  await cleanCacheOfImageStore()
  await cleanCacheOfProfileStore()
  emit('reload')

  showToast?.({
    props: {
      title: 'Reload is success',
      variant: 'success',
    },
  })
}

const { registerReloadTopic, registerOpenClose } = useHotkeys()
registerReloadTopic(() => reloadTopic())
registerOpenClose(() => emit('exit'))
</script>

<template>
  <BNavbar>
    <div class="d-flex align-items-center">
      <BNavbarBrand tag="h4" v-b-tooltip="title ? text : ''" class="mb-0 me-2" style="white-space: normal">
        {{ title }}
      </BNavbarBrand>
      <BLink
        icon
        :href="links.trackerSearchLink(title)"
        target="_blank"
        rel="noreferrer"
        :title="`Search for &quot;${title}&quot; on Tracker`">
        <BiBoxArrowUpRight />
      </BLink>
    </div>

    <BNavbarNav class="ms-auto mb-3 mb-lg-0">
      <BNavItem title="Reload the topic" @click.stop.prevent="reloadTopic">
        <BiArrowClockwise />
      </BNavItem>
      <BNavItem title="Show help" @click.stop.prevent="showHelp">
        <BiQuestionCircle />
      </BNavItem>
      <BNavItem title="Show settings" @click.stop.prevent="showSettings">
        <BiGear />
      </BNavItem>
      <BNavItem title="Exit" @click.stop.prevent="emit('exit')">
        <BiXCircle />
      </BNavItem>
    </BNavbarNav>
  </BNavbar>
</template>

<style scoped lang="scss">
// prevent tracker's style
.navbar-nav .nav-item {
  margin-left: 0;
}
</style>
