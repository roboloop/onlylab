import * as fs from 'node:fs/promises'
import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest'
import { profile } from './babepedia'
import { client } from './client'

import type { Mock } from 'vitest'
import type { Profile } from '@/services/store/profiles'

vi.mock('./client')

interface TestCase {
  source: string
  expected: Profile
}

const data: TestCase[] = [
  {
    source: __dirname + '/__mocks__/babepedia1.html',
    expected: {
      name: 'cpdmjio nyzhvfr',
      babeName: 'cpdmjio nyzhvfr',
      age: '25 ruwfd tsgfu',
      height: '867 cw',
      weight: '54 pn',
      country: 'ljelgp jkqggf',
      flag: undefined,
      nationality: undefined,
      boobs: 'agzn (acmkbbc gisfsouj)',
      braSize: '25r (89r)',
      updatedAt: '2024-01-01T00:00:00.000Z',
    },
  },
]

const fakeDate = new Date(Date.UTC(2024, 0, 1))
describe.each(data)('babepedia $source', ({ source, expected }: TestCase) => {
  beforeEach(() => {
    vi.useRealTimers()
    vi.setSystemTime(fakeDate)
  })
  afterEach(() => {
    vi.useRealTimers()
    vi.clearAllMocks()
  })
  it('getProfile', async () => {
    const file = await fs.readFile(source, 'utf-8')
    ;(client.send as Mock).mockResolvedValueOnce(file)

    const result = await profile('cpdmjio nyzhvfr', false)
    expect(client.send).toHaveBeenCalledWith({
      url: 'https://www.babepedia.com/babe/cpdmjio%20nyzhvfr',
    })
    expect(result).toEqual(expected)
  })
})
