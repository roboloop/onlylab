import localforage from 'localforage'
import _ from 'lodash'

export type Mode = 'overlay' | 'inject'
export interface Settings {
  main: {
    disabledOnForums: string[]
    skipSmallImages: boolean
  }

  // Ignored users' filters
  ignored: {
    actresses: string[]
    genres: string[]
    studios: string[]
  }

  // Babepedia integration
  babepedia: {
    enable: boolean
    badges: {
      fakeBoobs: boolean
      tattoos: boolean
      piercings: boolean
    }
  }

  // qBittorrent integration
  qbittorrent: {
    enable: boolean
    baseUrl: string
    savePath: string
    username: string
    password: string
  }

  // hidden
  mode: Mode
}

const defaultSettings: Settings = {
  main: {
    disabledOnForums: ['2', '15', '566', '1684', '1693', '1817'],
    skipSmallImages: true,
  },

  ignored: {
    actresses: [],
    genres: [],
    studios: [],
  },

  babepedia: {
    enable: true,
    badges: {
      fakeBoobs: false,
      tattoos: false,
      piercings: false,
    },
  },
  qbittorrent: {
    enable: false,
    baseUrl: '',
    savePath: '',
    username: '',
    password: '',
  },

  mode: 'inject',
} as const

export const store: LocalForage = localforage.createInstance({
  name: 'settings',
  driver: [localforage.LOCALSTORAGE],
})

// do not confuse with name
const key = 'settings'
export async function getSettings(): Promise<Settings> {
  // TODO: default's of disabledOnForums are permanent
  return _.merge(defaultSettings, await store.getItem<Settings>(key))
}

export async function putSettings(settings: Settings): Promise<void> {
  await store.setItem<Settings>(key, settings)
}
