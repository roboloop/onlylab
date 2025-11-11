import fs from 'node:fs/promises'
import { afterEach, describe, expect, it, vi } from 'vitest'
import { client } from '@/services/clients/client'
import { imgbox } from '@/services/host/imgbox'

import type { Mock } from 'vitest'

vi.mock('@/services/clients/client')

// TODO: no tests
describe.skip('imgbox', () => {
  afterEach(() => {
    vi.clearAllMocks()
  })

  it('happy path', async () => {
    const title = ''
    const href = ''
    const file = await fs.readFile(__dirname + '/__mocks__/imgbox1.html', 'utf-8')
    ;(client.send as Mock).mockResolvedValueOnce(file)

    const expected = ''

    const result = await imgbox.link(title, href)
    expect(result).toEqual(expected)
  })

  it('no href', async () => {
    const title = ''

    const expected = ''

    const result = await imgbox.link(title, undefined)
    expect(result).toEqual(expected)
  })

  it('support', async () => {
    const title = ''
    const expected = true

    const result = imgbox.support(title, undefined)
    expect(result).toEqual(expected)
  })
})
