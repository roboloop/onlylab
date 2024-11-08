import localforage from 'localforage'
import * as utils from '@/services/store/utils'

export interface Profile {
  name: string
  babeName?: string
  age?: string
  height?: string
  weight?: string
  country?: string
  flag?: string
  nationality?: string
  boobs?: string
  braSize?: string
  // pics?: string[]
  updatedAt: string
}

const store: LocalForage = localforage.createInstance({
  name: `profiles`,
  driver: [localforage.LOCALSTORAGE],
})

export async function getProfile(name: string): Promise<Profile | null> {
  return await store.getItem(name)
}

export async function putProfile(name: string, profile: Profile): Promise<void> {
  await store.setItem(name, profile)
}

export async function storeSize(): Promise<number> {
  return await utils.storeSize(store)
}
