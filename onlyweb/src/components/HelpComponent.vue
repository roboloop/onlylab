<script setup>
import hotkeys from '../services/hotkeys'
import { BModal, BButton } from 'bootstrap-vue'
import { ref } from 'vue'

const help = ref(null)
hotkeys.register('Slash', 'Open this help', { shiftKey: true }, () => {
  !help.value.isShow ? help.value.show() : help.value.hide()
})
const keys = ref([])
const actualizeRegistered = () => {
  keys.value.splice(0)
  keys.value = hotkeys
    .registered()
    .filter(({ desc }) => desc)
    .map(({ key, desc, opts }) => {
      const keys = []
      opts.ctrlKey && keys.push('Ctrl')
      opts.altKey && keys.push('Alt')
      opts.shiftKey && keys.push('Shift')
      keys.push(key)
      return {
        shortcut: keys.join(' + '),
        desc
      }
    })
}
</script>

<template>
  <b-modal ref="help" @show="actualizeRegistered" :no-fade="true" :static="true">
    <template #default>
      <ul>
        <li v-for="{ shortcut, desc } of keys" :key="shortcut">{{ shortcut }} — {{ desc }}</li>
      </ul>
    </template>
    <template #modal-header>
      <div class="w-100">Shortcuts</div>
    </template>
    <template #modal-footer>
      <div class="w-100">
        <b-button variant="primary" size="sm" class="float-right" @click="help.hide()">
          Close
        </b-button>
      </div>
    </template>
  </b-modal>
</template>

<style scoped lang="scss"></style>
