<script setup lang="ts">
import { BButton, BTable, useToast } from 'bootstrap-vue-next'
import { format } from 'date-fns'
import { saveAs } from 'file-saver'
import { filesize } from 'filesize'
import { computed, ref } from 'vue'
import { clearStore as clearImagesStore } from '@/services/store/images'
import { clearStore as clearProfilesStore } from '@/services/store/profiles'
import { getSettings, putSettings } from '@/services/store/settings'
import { clearStore as clearForumStore } from '@/services/store/state'
import { clearStore as clearTorrentStore } from '@/services/store/torrent'
import { localStoreSize } from '@/services/store/utils'
import { openFile, readFile } from '@/services/utils/fileUploader'

import type { TableFieldRaw } from 'bootstrap-vue-next'

interface DataUsageTableField {
  name: string
  size: number
}

const fields: TableFieldRaw<DataUsageTableField>[] = [{ key: 'name' }, { key: 'size' }]

const items = ref<DataUsageTableField[]>([])
const appName = import.meta.env.VITE_APP_NAME

const { create } = useToast()

async function onBackup(): Promise<void> {
  const settings = await getSettings()
  const json = JSON.stringify(settings, null, 2)
  const blob = new Blob([json], { type: 'application/json' })
  const date = format(new Date(), `dd.MM.yy_kk_mm_ss`)
  saveAs(blob, `${appName}_${date}.json`)

  create({
    title: 'Backup saved',
    variant: 'success',
    noProgress: true,
  })
}

async function onRestore(): Promise<void> {
  try {
    const file = await openFile('application/json')
    if (!file) {
      return
    }
    const contents = await readFile(file)
    const settings = JSON.parse(contents)
    await putSettings(settings)

    create({
      title: 'Backup restored. Reloading...',
      variant: 'success',
      noProgress: true,
    })

    setTimeout(() => location.reload(), 2000)
  } catch (err) {
    create({
      title: 'Failed to restore',
      variant: 'danger',
      body: (err as Error).message,
      noProgress: true,
    })
  }
}

async function onClear(): Promise<void> {
  await clearForumStore()
  await clearImagesStore()
  await clearProfilesStore()
  await clearTorrentStore()

  create({
    title: 'Cache cleared',
    variant: 'success',
    noProgress: true,
  })

  items.value.length && (await onShowUsage())
}

async function onShowUsage(): Promise<void> {
  items.value = Object.entries(localStoreSize()).map(([key, value]) => ({ name: key, size: value }))
}

const totalSize = computed((): number => items.value.reduce((acc, { size }) => acc + size, 0))
</script>

<template>
  <p>This tool allows you to backup and restore your {{ appName }} settings.</p>
  <div class="d-flex gap-2 align-items-center justify-content-center">
    <BButton type="button" color="black" @click.prevent.stop="onBackup">Save backup</BButton>
    <BButton type="button" color="black" @click.prevent.stop="onRestore">Restore backup</BButton>
  </div>
  <br />
  <hr />
  <p>
    As default localStorage is used as a storage of all data (settings and cache). Cleaning cache will not affect the
    Tracker session.
  </p>
  <div class="d-flex align-items-center justify-content-center">
    <BButton type="button" color="black" @click.prevent.stop="onClear">Clear cache</BButton>
  </div>
  <br />
  <a href="#" target="_blank" @click.prevent.stop="onShowUsage">Show usage</a>
  <BTable
    v-if="items.length"
    :items="items"
    :fields="fields"
    small
    head-variant="dark"
    foot-variant="dark"
    foot-clone
    no-footer-sorting
    striped>
    <template #cell(name)="{ item }">
      {{ item.name }}
    </template>

    <template #cell(size)="{ item }">
      {{ filesize(item.size, { standard: 'jedec' }) }}
    </template>

    <template #foot(name)> Total: </template>

    <template #foot(size)>
      {{ filesize(totalSize, { standard: 'jedec' }) }}
    </template>
  </BTable>
</template>

<style scoped lang="scss"></style>
