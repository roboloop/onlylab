import fs from 'node:fs/promises'
import { afterEach, describe, expect, it, vi } from 'vitest'
import { client } from '@/services/clients/client'
import { turboimagehost } from '@/services/host/turboimagehost'

import type { Mock } from 'vitest'

vi.mock('@/services/clients/client')

// TODO: no tests
describe.skip('turboimagehost', () => {
  afterEach(() => {
    vi.clearAllMocks()
  })

  it('happy path', async () => {
    const title = ''
    const href = ''
    const file = await fs.readFile(__dirname + '/__mocks__/turboimagehost1.html', 'utf-8')
    ;(client.send as Mock).mockResolvedValueOnce(file)

    const expected = ''

    const result = await turboimagehost.link(title, href)
    expect(result).toEqual(expected)
  })

  it('no href', async () => {
    const title = ''

    const expected = ''

    const result = await turboimagehost.link(title, undefined)
    expect(result).toEqual(expected)
  })

  it('support', async () => {
    const title = ''
    const expected = true

    const result = turboimagehost.support(title, undefined)
    expect(result).toEqual(expected)
  })
})
