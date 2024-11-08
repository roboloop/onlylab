import fs from 'node:fs/promises'
import { afterEach, describe, expect, it, vi } from 'vitest'
import { fastpic } from './fastpic'
import { client } from '@/services/clients/client'

import type { Mock } from 'vitest'

vi.mock('@/services/clients/client')

describe('fastpic', () => {
  afterEach(() => {
    vi.clearAllMocks()
  })

  it('happy path', async () => {
    const title = 'ezavi://q032.fastpic.org/wsquz/8502/0729/mt/_h20913h53ts3ef88m89oo3ynx6c3u3mt.fiug'
    const href = 'ezavi://fastpic.org/ydzj/032/8502/0729/_h20913h53ts3ef88m89oo3ynx6c3u3mt.wze.ftmf'
    const file = await fs.readFile(__dirname + '/__mocks__/fastpic1.html', 'utf-8')
    ;(client.send as Mock).mockResolvedValueOnce(file)

    const expected =
      'ezavi://q032.fastpic.org/big/8502/0729/mt/_h20913h53ts3ef88m89oo3ynx6c3u3mt.wze?ps0=rihk04ufyfwahfzngnfics&amp;qqtxfhl=7962712973'

    const result = await fastpic.link(title, href)
    expect(result).toEqual(expected)
  })

  it('no href', async () => {
    const title = 'ezavi://q032.fastpic.org/wsquz/8502/0729/mt/_h20913h53ts3ef88m89oo3ynx6c3u3mt.fiug'

    const expected = 'ezavi://q032.fastpic.org/wsquz/8502/0729/mt/_h20913h53ts3ef88m89oo3ynx6c3u3mt.fiug'

    const result = await fastpic.link(title, undefined)
    expect(result).toEqual(expected)
  })

  it('support', async () => {
    const title = 'ezavi://q032.fastpic.org/wsquz/8502/0729/mt/_h20913h53ts3ef88m89oo3ynx6c3u3mt.fiug'
    const expected = true

    const result = fastpic.support(title, undefined)
    expect(result).toEqual(expected)
  })
})
