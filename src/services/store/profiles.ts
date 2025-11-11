import localforage from 'localforage'

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
  bodyType?: string
  tattoos?: string
  piercings?: string
  updatedAt: string
}

export const store: LocalForage = localforage.createInstance({
  name: 'profiles',
  driver: [localforage.LOCALSTORAGE],
})

export async function getProfile(name: string): Promise<Profile | null> {
  return await store.getItem(name)
}

export async function putProfile(name: string, profile: Profile): Promise<void> {
  await store.setItem(name, profile)
}

export async function removeProfile(name: string): Promise<void> {
  await store.removeItem(name)
}

export async function clearStore(): Promise<void> {
  await store.clear()
}
