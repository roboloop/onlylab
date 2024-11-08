import localforage from 'localforage'
import * as utils from '@/services/store/utils'

const store: LocalForage = localforage.createInstance({
  name: `img`,
  driver: [localforage.LOCALSTORAGE],
})

export async function getImage(title: string): Promise<string | null> {
  return await store.getItem(title)
}

export async function putImage(title: string, href: string): Promise<void> {
  await store.setItem(title, href)
}

export async function storeSize(): Promise<number> {
  return await utils.storeSize(store)
}
