<script setup lang="ts">
import {
  BButton,
  BCard,
  BCardBody,
  BForm,
  BFormCheckbox,
  BFormGroup,
  BFormInput,
  BFormTextarea,
  BLink, BModal,
  BTab,
  BTabs,
} from 'bootstrap-vue-next'
import { onMounted, ref } from 'vue'
import DataComponent from '@/components/modals/DataComponent.vue'
import { getSettings, putSettings } from '@/services/store/settings'

import type { Settings } from '@/services/store/settings'
import {useHotkeys} from "@/composables/useHotkeys";

interface SettingsForm extends Settings {
  ignoredActressesText: string
  ignoredGenresText: string
  ignoredStudiosText: string
  ignoredForumsText: string
}

const form = ref<SettingsForm | null>(null)

onMounted(async () => {
  const settings: Settings = await getSettings()
  form.value = {
    ...settings,
    ignoredActressesText: settings.ignoredActresses.join('\n'),
    ignoredGenresText: settings.ignoredGenres.join('\n'),
    ignoredStudiosText: settings.ignoredStudios.join('\n'),
    ignoredForumsText: settings.ignoredForums.join('\n'),
  }
})

async function onSubmit(): Promise<void> {
  if (form.value === null) {
    return
  }

  const transform = (input: string): string[] =>
    input
      .split('\n')
      .map(g => g.trim())
      .filter(Boolean)

  const { ignoredActressesText, ignoredGenresText, ignoredStudiosText, ignoredForumsText, ...rest } = form.value
  const settings: Settings = {
    ...rest,
    ignoredActresses: transform(ignoredActressesText),
    ignoredGenres: transform(ignoredGenresText),
    ignoredStudios: transform(ignoredStudiosText),
    ignoredForums: transform(ignoredForumsText),
  }

  await putSettings(settings)

  settingsRef.value?.hide()
}

const settingsRef = ref<InstanceType<typeof BModal> | null>(null)
const isShown = ref<boolean>(false)
const { registerHotkey } = useHotkeys()
registerHotkey({ mac: 'shift+,', win: 'shift+,' }, 'Open settings', () => {
  // TODO: reset form
  isShown.value ? settingsRef.value?.hide() : settingsRef.value?.show()
})

</script>

<template>
  <!--TODO: bodyScrolling depends on fullscreen  -->
  <BModal
    id="settings"
    ref="settingsRef"
    v-model="isShown"
    noFade
    teleportTo="#app"
    @hide.prevent
    hideHeader
    hideFooter
    bodyScrolling
    size="xl"
  >
    <template #default>
      <BForm v-if="form" :state="form" class="space-y-4" @submit.prevent.stop="onSubmit">
        <BTabs class="w-full">
          <BTab title="Basic">
            <BCard>
              <template #header>
                <h6>Control the flow of basic settings</h6>
              </template>
              <BCardBody>
                <BFormCheckbox v-model="form.enable">Enable on startup</BFormCheckbox>

                <BFormCheckbox v-model="form.fullscreen">Fullscreen mode</BFormCheckbox>

                <BFormGroup label="Ignored actresses (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredActressesText" autoresize placeholder="Something..." />
                </BFormGroup>

                <BFormGroup label="Ignored genres (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredGenresText" autoresize placeholder="Something..." />
                </BFormGroup>

                <BFormGroup label="Ignored studios (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredStudiosText" autoresize placeholder="Something..." />
                </BFormGroup>

                <BFormGroup label="Ignored forums (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredForumsText" autoresize placeholder="Something..." />
                </BFormGroup>
              </BCardBody>

              <template #footer>
                <BButton type="submit" color="black">Save</BButton>
              </template>
            </BCard>
          </BTab>

          <BTab title="Babepedia">
            <BCard>
              <template #header>
                <h6>
                  Integration with
                  <BLink to="https://www.babepedia.com/" rel="noreferrer" target="_blank"> babepedia.com </BLink>
                </h6>
              </template>

              <BFormCheckbox v-model="form.babepedia.enable">Enable</BFormCheckbox>
              <BFormCheckbox v-model="form.babepedia.enableOnTopic" :disabled="!form.babepedia.enable"
              >Enable on topic
              </BFormCheckbox>
              <BFormCheckbox v-model="form.babepedia.enableOnForum" :disabled="!form.babepedia.enable"
              >Enable on forum
              </BFormCheckbox>

              <template #footer>
                <BButton type="submit" color="black"> Save</BButton>
              </template>
            </BCard>
          </BTab>

          <BTab title="qBittorrent">
            <BCard>
              <template #header>
                <h6>
                  Integration with
                  <BLink
                    to="https://github.com/qbittorrent/qBittorrent/wiki#WebUI-API"
                    active-class="text-primary"
                    inactive-class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                    rel="noreferrer"
                    target="_blank">
                    qBittorrent (WebUI)
                  </BLink>
                  via HTTP API
                </h6>
              </template>

              <BFormCheckbox v-model="form.qbittorrent.enable">Enable</BFormCheckbox>
              <BFormGroup label="Base url" class="mt-2">
                <BFormInput
                  v-model="form.qbittorrent.baseUrl"
                  label="Base url"
                  placeholder="http://localhost:8080"
                  :disabled="!form.qbittorrent.enable" />
              </BFormGroup>
              <BFormGroup label="Save path">
                <BFormInput
                  v-model="form.qbittorrent.savePath"
                  label="Save path"
                  placeholder="/home/media/"
                  :disabled="!form.qbittorrent.enable" />
              </BFormGroup>
              <BFormGroup label="Username">
                <BFormInput v-model="form.qbittorrent.username" label="Username" :disabled="!form.qbittorrent.enable" />
              </BFormGroup>
              <BFormGroup label="Password">
                <BFormInput
                  v-model="form.qbittorrent.password"
                  label="Password"
                  type="password"
                  :disabled="!form.qbittorrent.enable" />
              </BFormGroup>
              <template #footer>
                <BButton type="submit" color="black">Save</BButton>
              </template>
            </BCard>
          </BTab>

          <BTab title="Data">
            <BCard>
              <template #header>
                <h6>Data management</h6>
              </template>

              <DataComponent />
            </BCard>
          </BTab>
        </BTabs>
      </BForm>
    </template>
  </BModal>
</template>

<style scoped lang="scss"></style>
