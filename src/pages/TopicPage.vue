<script setup lang="ts">
import { BContainer } from 'bootstrap-vue-next'
import _ from 'lodash'
import { storeToRefs } from 'pinia'
import { nextTick, ref } from 'vue'
import ExtraInfoComponent from '@/components/ExtraInfoComponent.vue'
import FilesComponent from '@/components/FilesComponent.vue'
import ImagesComponent from '@/components/ImagesComponent.vue'
import NavbarComponent from '@/components/NavbarComponent.vue'
import OverlayMode from '@/components/parts/OverlayMode.vue'
import TrackerLinksList from '@/components/parts/TrackerLinksList.vue'
import ProfileComponent from '@/components/ProfileComponent.vue'
import QbittorrentComponent from '@/components/QbittorrentComponent.vue'
import ScreenlistsComponent from '@/components/ScreenlistsComponent.vue'
import ViewModeComponent from '@/components/ViewModeComponent.vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { addInjectPlace } from '@/services/dom/injector'
import { parseTopic } from '@/services/dom/topic'
import { parseGenre } from '@/services/parsers/genre'
import { parseName, parseNames } from '@/services/parsers/name'
import { parseQuality } from '@/services/parsers/quality'
import { parseStudio } from '@/services/parsers/studio'
import { parseTitle } from '@/services/parsers/title'
import { parseYear } from '@/services/parsers/year'
import { getSettings } from '@/services/store/settings'
import { localSort } from '@/services/utils/array'
import * as links from '@/services/utils/links'
import { useImageStore } from '@/stores/image'
import { useProfileStore } from '@/stores/profile'
import { useQbittorrentStore } from '@/stores/qbittorrent'
import { useViewModeStore } from '@/stores/viewMode'

const { createdAt, downloadLink, duration, forums, forumName, imageNodes, seeds, size, text, topic } =
  parseTopic(document)

function onReload() {
  showViewMode.value = false
  nextTick(() => {
    showViewMode.value = true
  })
}

// left side
const showViewMode = ref<boolean>(true)
const {
  main: { disabledOnForums },
  babepedia: { enable: babepediaEnable },
  qbittorrent: { enable: qbittorrentEnabled },
} = await getSettings()

const show = ref(true)

// center
const { loadImages } = useImageStore()
loadImages(imageNodes)

const viewModeStore = useViewModeStore()
const { viewMode, imageLinks, pack, files } = storeToRefs(viewModeStore)

// right side
const genres = localSort(parseGenre(text))
const studious = localSort(parseStudio(text))
const year = parseYear(text)
const title = parseTitle(text)
const names = parseNames(title)
const profileStore = useProfileStore()
profileStore.loadProfiles(names)
const { normalizedProfiles } = storeToRefs(profileStore)

// Global
const { setPlaceholder, setDownloadLink } = useQbittorrentStore()
const quality = parseQuality(text)
const actresses = parseName(title)
setPlaceholder(forumName, quality, actresses, studious)
setDownloadLink(downloadLink)

const { registerOpenBabepedia, registerOpenTracker } = useHotkeys()
registerOpenBabepedia(() => {
  const name = normalizedProfiles?.value?.[0]?.babeName
  name && window.open(links.babepediaLink(name), '_blank')
})
registerOpenTracker(() => {
  const name = normalizedProfiles?.value?.[0]?.name
  name && window.open(links.trackerSearchLink(name), '_blank')
})

addInjectPlace(document)
</script>

<template>
  <OverlayMode :show="show" v-if="_.intersection(disabledOnForums, forums).length === 0">
    <NavbarComponent :text="text" @exit="show = !show" @reload="onReload" />
    <BContainer fluid>
      <div class="row">
        <div class="col-sm-2 mt-1">
          <ViewModeComponent v-if="showViewMode" :topic="topic" :text="text" />
          <QbittorrentComponent
            v-if="qbittorrentEnabled"
            :text="text"
            :topic="topic"
            :forumName="forumName"
            :downloadLink="downloadLink" />
          <ExtraInfoComponent
            :text="text"
            :topic="topic"
            :createdAt="createdAt"
            :seeds="seeds"
            :duration="duration"
            :size="size"></ExtraInfoComponent>
        </div>
        <div class="col-sm-8">
          <ImagesComponent v-if="viewMode === 'images'" :imageLinks="imageLinks" />
          <ScreenlistsComponent v-if="viewMode === 'pack'" :imageLinks="pack" />
          <FilesComponent v-if="viewMode === 'files'" :files="files" />
        </div>

        <div class="col-sm-2 mt-1">
          <template v-if="babepediaEnable">
            <ProfileComponent
              v-for="profile in normalizedProfiles"
              :key="profile.babeName"
              :profile="profile"
              :year="year" />
          </template>
          <TrackerLinksList :entities="genres" name="Genres" emptyMessage="No genres" />
          <TrackerLinksList :entities="studious" name="Studios" emptyMessage="No studios" />
        </div>
      </div>
    </BContainer>
  </OverlayMode>
</template>

<style scoped lang="scss"></style>
