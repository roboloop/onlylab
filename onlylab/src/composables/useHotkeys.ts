import hotkeys from 'hotkeys-js'
import { onBeforeUnmount, onMounted, ref } from 'vue'

type Platform = 'win' | 'mac'
type Handler = () => void
export type HotkeySet = Record<Platform, string>

export interface RegisteredHotkey {
  hotkey: string
  desc: string
}

const platform: Platform = navigator.userAgent.toLowerCase().includes('mac') ? 'mac' : 'win'
export const registeredHotkeys = ref<RegisteredHotkey[]>([])

export function useHotkeys() {
  function registerHotkey(hotkeySet: HotkeySet, desc: string, fn: Handler): void {
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

  // Main
  function registerReloadTopic(fn: Handler): void {
    registerHotkey({ mac: 'control+R', win: 'alt+R' }, 'Reload topic', fn)
  }

  function registerOpenHelp(fn: Handler): void {
    registerHotkey({ mac: 'shift+/', win: 'shift+/' }, 'Open this help', fn)
  }

  function registerOpenSettings(fn: Handler): void {
    registerHotkey({ mac: 'shift+,', win: 'shift+,' }, 'Open settings', fn)
  }

  function registerOpenClose(fn: Handler): void {
    registerHotkey({ mac: 'control+A', win: 'alt+A' }, 'Open/Close app', fn)
  }

  // Qbittorrent
  function registerDownload(fn: Handler): void {
    registerHotkey({ mac: 'control+D', win: 'shift+D' }, 'Download topic', fn)
  }

  function registerAddToQueue(fn: Handler): void {
    registerHotkey({ mac: 'control+Q', win: 'shift+Q' }, 'Add topic to queue', fn)
  }

  function registerRemove(fn: Handler): void {
    registerHotkey({ mac: 'control+E', win: 'shift+E' }, 'Remove topic', fn)
  }

  // Image
  function registerPrevImage(fn: Handler): void {
    registerHotkey({ mac: 'left', win: 'left' }, 'Prev image', fn)
  }

  function registerNextImage(fn: Handler): void {
    registerHotkey({ mac: 'right', win: 'right' }, 'Next image', fn)
  }

  // Babepedia profile
  function registerOpenBabepedia(fn: Handler): void {
    registerHotkey({ mac: 'control+B', win: 'alt+B' }, 'Open the first babepedia link', fn)
  }

  function registerOpenTracker(fn: Handler): void {
    registerHotkey({ mac: 'control+L', win: 'alt+L' }, 'Open the first tracker search link', fn)
  }

  // Forum
  function registerFocusOnSearch(fn: Handler): void {
    registerHotkey({ mac: 'control+F', win: 'shift+F' }, 'Focus on the search line', fn)
  }

  function registerPreviousPage(fn: Handler): void {
    registerHotkey({ mac: 'option+left', win: 'alt+left' }, 'Previous page', fn)
  }

  function registerNextPage(fn: Handler): void {
    registerHotkey({ mac: 'option+right', win: 'alt+right' }, 'Next page', fn)
  }

  return {
    registerReloadTopic,
    registerOpenHelp,
    registerOpenSettings,
    registerOpenClose,

    registerDownload,
    registerAddToQueue,
    registerRemove,

    registerPrevImage,
    registerNextImage,

    registerOpenBabepedia,
    registerOpenTracker,

    registerFocusOnSearch,
    registerPreviousPage,
    registerNextPage,
  }
}
