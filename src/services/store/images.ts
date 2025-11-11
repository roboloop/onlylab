import localforage from 'localforage'

export const store: LocalForage = localforage.createInstance({
  name: 'img',
  driver: [localforage.LOCALSTORAGE],
})

export async function getImage(title: string): Promise<string | null> {
  return await store.getItem(title)
}

export async function putImage(title: string, href: string): Promise<void> {
  await store.setItem(title, href)
}

export async function removeImage(title: string): Promise<void> {
  await store.removeItem(title)
}

export async function clearStore(): Promise<void> {
  await store.clear()
}
