import localforage from 'localforage'
import * as utils from '@/services/store/utils'

export interface Forum {
  filter: string
  hideIgnored: boolean
}

const store: LocalForage = localforage.createInstance({
  name: `forum`,
  driver: [localforage.LOCALSTORAGE],
})

const key = 'forum'
const defaultForum: Forum = {
  filter: '',
  hideIgnored: true,
}

export async function getForum(): Promise<Forum> {
  return { ...defaultForum, ...(await store.getItem<Forum>(key)) }
}

export async function putForum(forum: Forum): Promise<void> {
  await store.setItem<Forum>(key, forum)
}

export async function storeSize(): Promise<number> {
  return await utils.storeSize(store)
}
