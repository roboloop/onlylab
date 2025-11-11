import { afterEach, describe, expect, it, vi } from 'vitest'
import { client } from './client'
import { freeSpace, list, remove, upload } from './qbittorrent'
import { extractName } from '@/services/utils/torrent'

import type { Mock } from 'vitest'
import type { Placeholder } from '@/services/utils/strings'

vi.mock('./client')
vi.mock('@/services/utils/torrent')

describe('qbittorrent $source', () => {
  afterEach(() => {
    vi.clearAllMocks()
  })
  it('upload', async () => {
    ;(client.send as Mock).mockResolvedValueOnce('Ok.')

    const torrent = new Blob()
    const paused = false
    const placeholder: Placeholder = {
      date: new Date(),
      forum: '',
      quality: '',
      studios: [],
      actresses: [],
    }

    await expect(upload(torrent, paused, placeholder)).resolves.toBeUndefined()
  })

  it('list', async () => {
    ;(client.send as Mock).mockResolvedValueOnce('[{"name": "foo"}]')

    const result = await list()
    expect(result).toEqual([{ name: 'foo' }])
  })

  it('remove', async () => {
    ;(extractName as Mock).mockResolvedValueOnce('foo')
    ;(client.send as Mock)
      .mockResolvedValueOnce('[{"name": "foo", "state": "error"},{"name": "bar","state":"queuedUP"}]')
      .mockResolvedValueOnce('Ok.')

    const torrent = new Blob()

    await expect(remove(torrent)).resolves.toBeUndefined()
  })

  it('freeSpace', async () => {
    ;(client.send as Mock).mockResolvedValueOnce('{"server_state": {"free_space_on_disk": 42}}')

    const result = await freeSpace()
    expect(result).toEqual(42)
  })
})
