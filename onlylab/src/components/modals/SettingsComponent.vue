<script setup lang="ts">
import BiInfoCircle from '~icons/bi/info-circle'
import {
  BButton,
  BCard,
  BCardBody,
  BForm,
  BFormCheckbox,
  BFormGroup,
  BFormInput,
  BFormTextarea,
  BLink,
  BModal,
  BPopover,
  BTab,
  BTable,
  BTabs,
  useToastController,
} from 'bootstrap-vue-next'
import _ from 'lodash'
import { onMounted, ref, useTemplateRef, watch } from 'vue'
import DataComponent from '@/components/modals/DataComponent.vue'
import { useHotkeys } from '@/composables/useHotkeys'
import { baseId } from '@/services/dom/injector'
import { getSettings, putSettings } from '@/services/store/settings'
import { substitutes } from '@/services/utils/strings'

import type { TableFieldRaw } from 'bootstrap-vue-next'
import type { Settings } from '@/services/store/settings'

interface SettingsForm extends Settings {
  ignoredActressesText: string
  ignoredGenresText: string
  ignoredStudiosText: string
  disabledOnForumsText: string
}

const form = ref<SettingsForm | null>(null)
async function loadSettings() {
  const settings = await getSettings()
  form.value = {
    ...settings,
    ignoredActressesText: settings.ignored.actresses.join('\n'),
    ignoredGenresText: settings.ignored.genres.join('\n'),
    ignoredStudiosText: settings.ignored.studios.join('\n'),
    disabledOnForumsText: settings.main.disabledOnForums.join('\n'),
  }
}

onMounted(loadSettings)

async function onSubmit(): Promise<void> {
  if (form.value === null) {
    return
  }

  const transform = (input: string): string[] =>
    input
      .split('\n')
      .map(g => g.trim())
      .filter(Boolean)

  const {
    ignoredActressesText,
    ignoredGenresText,
    ignoredStudiosText,
    disabledOnForumsText,
    main: { skipSmallImages },
    ...rest
  } = form.value
  const settings: Settings = {
    ...rest,
    ignored: {
      actresses: _.uniq(transform(ignoredActressesText)),
      genres: _.uniq(transform(ignoredGenresText)),
      studios: _.uniq(transform(ignoredStudiosText)),
    },
    main: {
      disabledOnForums: _.uniq(transform(disabledOnForumsText)),
      skipSmallImages,
    },
  }

  await putSettings(settings)

  changesWereMade.value = false
  settingsRef.value?.hide()
  showToast?.({
    props: {
      title: 'Settings have been saved. Reloading...',
      variant: 'success',
    },
  })
  setTimeout(() => location.reload(), 2000)
}

const changesWereMade = ref<boolean>(false)
watch(
  form,
  (newValue, oldValue) => {
    if (oldValue !== null) {
      changesWereMade.value = true
    }
  },
  { deep: true },
)

const { show: showToast } = useToastController()
async function onHidden() {
  if (changesWereMade.value) {
    showToast?.({
      props: {
        title: "Settings haven't been saved",
        variant: 'warning',
      },
    })
  }
  await loadSettings()
  changesWereMade.value = false
}

const { mode } = await getSettings()
const allowModeChoice = import.meta.env.VITE_SHOW_HIDDEN === 'true'

interface SubstitutesField {
  code: string
  desc: string
  example: string
}
const substituteFields = ref<TableFieldRaw<SubstitutesField>[]>([{ key: 'code' }, { key: 'desc' }, { key: 'example' }])
const substituteItems = Object.entries(substitutes).map(([code, [, desc, example]]) => ({ code, desc, example }))

const settingsRef = useTemplateRef<typeof BModal>('settingsRef')
const isShown = ref<boolean>(false)
const { registerOpenSettings } = useHotkeys()
registerOpenSettings(() => (isShown.value ? settingsRef.value?.hide() : settingsRef.value?.show()))
</script>

<template>
  <BModal
    id="settings"
    ref="settingsRef"
    v-model="isShown"
    :teleportTo="baseId"
    noFade
    hideHeader
    hideFooter
    :bodyScrolling="mode === 'overlay'"
    size="xl"
    @hidden="onHidden">
    <template #default>
      <BForm v-if="form" :state="form" class="space-y-4" @submit.prevent.stop="onSubmit">
        <BTabs class="w-full">
          <BTab title="Basic">
            <BCard>
              <template #header>
                <h6>Control the flow of basic settings</h6>
              </template>
              <BCardBody>
                <BFormCheckbox v-if="allowModeChoice" v-model="form.mode" value="overlay" unchecked-value="inject">
                  Overlay mode
                </BFormCheckbox>

                <BFormGroup label="Disabled topic parsing on forums (separated by a new line)">
                  <BFormTextarea v-model="form.disabledOnForumsText" placeholder="Something..." rows="3" />
                </BFormGroup>

                <BFormCheckbox v-model="form.main.skipSmallImages">
                  <!-- TODO: global var for 128px -->
                  Auto skip small images that are less than 128px in height or weight, like flags or banners
                  <span>(experimental)</span>
                </BFormCheckbox>
              </BCardBody>

              <template #footer>
                <BButton type="submit" color="black">Save</BButton>
              </template>
            </BCard>
          </BTab>

          <BTab title="Ignoring">
            <BCard>
              <template #header>
                <h6>Set up what you want to ignore</h6>
              </template>
              <BCardBody>
                <BFormGroup label="Ignored actresses (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredActressesText" placeholder="Something..." rows="3" />
                </BFormGroup>

                <BFormGroup label="Ignored genres (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredGenresText" placeholder="Something..." rows="3" />
                </BFormGroup>

                <BFormGroup label="Ignored studios (separated by a new line)">
                  <BFormTextarea v-model="form.ignoredStudiosText" placeholder="Something..." rows="3" />
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
              <BFormCheckbox v-model="form.babepedia.badges.fakeBoobs" :disabled="!form.babepedia.enable">
                Show "Fake Boobs" badge to the right of the topic <span>(experimental)</span>
              </BFormCheckbox>
              <BFormCheckbox v-model="form.babepedia.badges.tattoos" :disabled="!form.babepedia.enable">
                Show "Tattoos" badge to the right of the topic <span>(experimental)</span>
              </BFormCheckbox>
              <BFormCheckbox v-model="form.babepedia.badges.piercings" :disabled="!form.babepedia.enable">
                Show "Piercings" badge to the right of the topic <span>(experimental)</span>
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
              <BFormGroup>
                <template #label>
                  <div class="d-flex align-items-center">
                    <span class="me-2" style="font-size: inherit">Save path</span>
                    <BPopover
                      close-on-hide
                      placement="right"
                      :delay="{ show: 0, hide: 0 }"
                      style="max-width: 512px; width: 512px">
                      <template #target>
                        <BLink icon href="#" target="_blank" @click.prevent.stop="">
                          <BiInfoCircle />
                        </BLink>
                      </template>
                      <template #title>List of available substitutes</template>
                      <BTable :items="substituteItems" :fields="substituteFields" head-variant="dark" small striped />
                      <span>
                        Example: <code>/home/videos/%Y/%q/</code> will render as
                        <code>/home/videos/2024/1080p/</code>
                      </span>
                    </BPopover>
                  </div>
                </template>

                <BFormInput
                  v-model="form.qbittorrent.savePath"
                  label="Save path"
                  placeholder="/home/media/"
                  :disabled="!form.qbittorrent.enable" />
              </BFormGroup>
              <BFormGroup label="Username">
                <BFormInput
                  v-model="form.qbittorrent.username"
                  label="Username"
                  disabled
                  placeholder="No support yet" />
              </BFormGroup>
              <BFormGroup label="Password">
                <BFormInput
                  v-model="form.qbittorrent.password"
                  label="Password"
                  type="password"
                  disabled
                  placeholder="No support yet" />
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

<style scoped lang="scss">
.form-check,
fieldset {
  margin-bottom: 0.5em;
}
</style>
