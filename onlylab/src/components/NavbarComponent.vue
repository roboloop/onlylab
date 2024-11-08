<script setup lang="ts">
import BiGear from '~icons/bi/gear?width=1.75em&height=1.75em'
import BiQuestionCircle from '~icons/bi/question-circle?width=1.75em&height=1.75em'
import BiXCircle from '~icons/bi/x-circle?width=1.75em&height=1.75em'
import BiArrowClockwise from '~icons/bi/arrow-clockwise?width=1.75em&height=1.75em';
import BiBoxArrowUpRight from '~icons/bi/box-arrow-up-right?width=1em&height=1em'

import {
  BLink,
  BNavbar,
  BNavItem,
  BNavbarBrand,
  BNavbarNav,
  vBTooltip,
  useModal,
} from 'bootstrap-vue-next'
import {parseTitle} from '@/services/parsers/title'
import * as links from "@/services/utils/links";
import {useProfiles} from "@/composables/useProfiles";

const props = defineProps<{
  text: string
}>()

const title = parseTitle(props.text) || props.text
const emit = defineEmits<{
  reload: []
  exit: []
}>()

const {show: showHelp} = useModal('help')
const {show: showSettings} = useModal('settings')

const { reloadProfiles } = useProfiles(props.text)
async function reloadTopic() {
  await reloadProfiles()

}
</script>

<template>
  <BNavbar>
    <div class="d-flex align-items-center">
      <BNavbarBrand tag="h4" v-b-tooltip="title ? text : ''" class="mb-0 me-2">
        {{ title }}
      </BNavbarBrand>
      <BLink icon :href="links.trackerSearchLink(title)" target="_blank" rel="noreferrer"
             :title='`Check out "${title}" on Tracker`'>
        <BiBoxArrowUpRight/>
      </BLink>
    </div>

    <BNavbarNav class="ms-auto mb-3 mb-lg-0">
      <BNavItem title="Reload the topic" @click.stop.prevent="reloadTopic">
        <BiArrowClockwise/>
      </BNavItem>
      <BNavItem title="Show help" @click.stop.prevent="showHelp">
        <BiQuestionCircle/>
      </BNavItem>
      <BNavItem title="Show settings" @click.stop.prevent="showSettings">
        <BiGear/>
      </BNavItem>
      <BNavItem title="Exit" @click.stop.prevent="emit('exit')">
        <BiXCircle/>
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
