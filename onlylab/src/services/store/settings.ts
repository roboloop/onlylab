import localforage from 'localforage'
import * as utils from '@/services/store/utils'

export interface Settings {
  enable: boolean
  ignoredActresses: string[]
  ignoredGenres: string[]
  ignoredStudios: string[]
  ignoredForums: string[]

  // Integrations
  babepedia: {
    enable: boolean
    enableOnTopic: boolean
    enableOnForum: boolean
  }
  qbittorrent: {
    enable: boolean
    baseUrl: string
    savePath: string
    username: string
    password: string
  }

  // hidden
  fullscreen: boolean
}

const defaultSettings: Settings = {
  enable: false,
  ignoredActresses: [],
  ignoredGenres: [],
  ignoredStudios: [],
  ignoredForums: [],
  // Integrations
  babepedia: {
    enable: false,
    enableOnTopic: true,
    enableOnForum: false,
  },
  qbittorrent: {
    enable: false,
    baseUrl: '',
    savePath: '',
    username: '',
    password: '',
  },

  fullscreen: false,
} as const

const store: LocalForage = localforage.createInstance({
  name: `settings`,
  driver: [localforage.LOCALSTORAGE],
})

export async function getSettings(): Promise<Settings> {
  return { ...defaultSettings, ...(await store.getItem<Settings>('settings')) }
}

export async function putSettings(settings: Settings): Promise<void> {
  await store.setItem<Settings>('settings', settings)
}

export async function storeSize(): Promise<number> {
  return await utils.storeSize(store)
}
