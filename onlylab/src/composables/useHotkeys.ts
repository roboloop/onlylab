import hotkeys from 'hotkeys-js'
import { onBeforeUnmount, onMounted, ref } from 'vue'

type Platform = 'win' | 'mac'
export type HotkeySet = Record<Platform, string>

export interface RegisteredHotkey {
  hotkey: string
  desc: string
}

const platform: Platform = navigator.userAgent.toLowerCase().includes('mac') ? 'mac' : 'win'
export const registeredHotkeys = ref<RegisteredHotkey[]>([])

export function useHotkeys() {
  function registerHotkey(hotkeySet: HotkeySet, desc: string, fn: () => void): void {
    const hotkey = hotkeySet[platform]
    if (!hotkey) {
      throw new Error('no hotkey has been set')
    }

    onMounted(() => {
      registeredHotkeys.value.push({ hotkey, desc })
      hotkeys(hotkey, fn)
    })

    onBeforeUnmount(() => {
      registeredHotkeys.value = registeredHotkeys.value.filter(({ hotkey: rHotkey }) => hotkey !== rHotkey)
      hotkeys.unbind(hotkey)
    })
  }

  return {
    registerHotkey,
  }
}
