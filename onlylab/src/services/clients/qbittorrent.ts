import _ from 'lodash'
import { client } from './client'
import { getSettings } from '@/services/store/settings'
import { interpolate } from '@/services/utils/strings'
import { extractName } from '@/services/utils/torrent'

import type { MainData, Torrent } from 'qbittorrent-api-v2'
import type { Placeholder } from '@/services/utils/strings'

export interface File {
  name: string
  size: number
}

const addEndpoint: string = '/api/v2/torrents/add'
const listEndpoint: string = '/api/v2/torrents/info'
const deleteEndpoint: string = '/api/v2/torrents/delete'
const maindataEndpoint: string = '/api/v2/sync/maindata'

export async function upload(torrent: Blob, paused: boolean, placeholder: Placeholder): Promise<void> {
  const {
    qbittorrent: { baseUrl, savePath },
  } = await getSettings()

  const blob = new Blob([torrent])
  const file = new File([blob], 'name.torrent', { type: 'application/x-bittorrent' })
  const data = new FormData()
  data.append('torrents', file)
  data.append('autoTMM', 'false')
  data.append('rename', '')
  data.append('paused', String(paused))

  if (savePath) {
    const folder = interpolate(savePath, placeholder)
    data.append('savepath', folder)
  }

  const url = _.trimEnd(baseUrl, '/') + addEndpoint
  const response = await client.send({ method: 'POST', url, data })

  if (response !== 'Ok.') {
    throw new Error("Torrent hasn't been uploaded")
  }
}

export async function list(): Promise<Torrent[]> {
  const {
    qbittorrent: { baseUrl },
  } = await getSettings()
  const params = new URLSearchParams({
    filter: 'all',
  })
  const url = _.trimEnd(baseUrl, '/') + listEndpoint + '?' + params.toString()
  const response = await client.send({ method: 'GET', url })

  return JSON.parse(response)
}

export async function remove(torrent: Blob): Promise<void> {
  const name = await extractName(torrent)
  if (!name) {
    throw new Error("Torrent name hasn't been found")
  }
  const topic = (await list()).find(t => t.name === name)
  if (!topic) {
    throw new Error("Torrent hasn't been found")
  }

  const statuses: string[] = ['error', 'allocating', 'downloading', 'metaDL', 'pausedDL', 'queuedDL', 'stalledDL']
  const deleteFiles: boolean = statuses.includes(topic.state)
  const params = new URLSearchParams({
    hashes: topic.hash,
    deleteFiles: String(deleteFiles),
  })

  const {
    qbittorrent: { baseUrl },
  } = await getSettings()
  const url = _.trimEnd(baseUrl, '/') + deleteEndpoint + '?' + params.toString()

  await client.send({ method: 'GET', url })
}

export async function freeSpace(): Promise<number | undefined> {
  const {
    qbittorrent: { baseUrl },
  } = await getSettings()
  const url = baseUrl + maindataEndpoint
  const response = await client.send({ method: 'GET', url })

  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  return ((JSON.parse(response) as MainData)?.server_state as any)?.free_space_on_disk
}
