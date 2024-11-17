import localforage from 'localforage'

export interface TorrentStatus {
  downloadedAt?: Date
}

export const store: LocalForage = localforage.createInstance({
  name: 'torrent',
  driver: [localforage.LOCALSTORAGE],
})

const defaultTorrentStatus: TorrentStatus = {}

export async function getTorrentStatus(topic: string): Promise<TorrentStatus> {
  return { ...defaultTorrentStatus, ...(await store.getItem<TorrentStatus>(topic)) }
}

export async function markAsDownloaded(topic: string): Promise<void> {
  const status = await getTorrentStatus(topic)
  status.downloadedAt = new Date()
  await store.setItem<TorrentStatus>(topic, status)
}

export async function markAsRemoved(topic: string): Promise<void> {
  const status = await getTorrentStatus(topic)
  status.downloadedAt = undefined
  await store.setItem<TorrentStatus>(topic, status)
}

export async function clearStore(): Promise<void> {
  await store.clear()
}
