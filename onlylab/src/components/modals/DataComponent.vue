<script setup lang="ts">
import { BButton, BTable } from 'bootstrap-vue-next'
import { filesize } from 'filesize'
import { computed, ref } from 'vue'
import * as forum from '@/services/store/forum'
import * as images from '@/services/store/images'
import * as profiles from '@/services/store/profiles'
import * as settings from '@/services/store/settings'
import * as torrent from '@/services/store/torrent'

import type { TableFieldRaw } from 'bootstrap-vue-next'

interface DataUsageTableField {
  name: string
  size: string
  action: any
}

const fields = ref<TableFieldRaw<DataUsageTableField>[]>([{ key: 'name' }, { key: 'size' }, { key: 'action' }])

const items = ref<{ name: string; size: number }[]>([])

async function onBackupSettings() {
  // TODO:
}

async function onBackup() {
  // TODO:
}

async function onRestore(event: Event) {
  // TODO
  console.log('event', event)
}

async function onClear() {
  // TODO:
}

async function onShowUsage(): Promise<void> {
  const [forumSize, imagesSize, profilesSize, settingsSize, torrentSize] = await Promise.all([
    forum.storeSize(),
    images.storeSize(),
    profiles.storeSize(),
    settings.storeSize(),
    torrent.storeSize(),
  ])

  items.value = [
    { name: 'Forum', size: forumSize },
    { name: 'Images', size: imagesSize },
    { name: 'Profiles', size: profilesSize },
    { name: 'Settings', size: settingsSize },
    { name: 'Torrent', size: torrentSize },
  ]
}

const totalSize = computed((): number => items.value.reduce((acc, { size }) => acc + size, 0))
</script>

<template>
  <BButton type="button" color="black" @click.prevent.stop="onBackupSettings">Save backup (settings only)</BButton>
  <BButton type="button" color="black" @click.prevent.stop="onBackup">Save backup (settings only)</BButton>
  <BButton type="button" color="black" @click.prevent.stop="onRestore">Restore backup</BButton>
  <BButton type="button" color="black" @click.prevent.stop="onClear">Clear cache</BButton>
  <br />
  <a href="#" target="_blank" @click.prevent.stop="onShowUsage">Show usage</a>
  <BTable
    v-if="items.length"
    :items="items"
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
    show-empty>
    <template #cell(name)="{ item }">
      {{ item.name }}
    </template>

    <template #cell(size)="{ item }">
      {{ filesize(item.size, { standard: 'jedec' }) }}
    </template>

    <template #foot(size)>
      {{ filesize(totalSize, { standard: 'jedec' }) }}
    </template>
  </BTable>
</template>

<style scoped lang="scss"></style>
