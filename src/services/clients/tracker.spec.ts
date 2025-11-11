import * as fs from 'node:fs/promises'
import { afterEach, describe, expect, it, vi } from 'vitest'
import { client } from './client'
import { files } from './tracker'

import type { Mock } from 'vitest'
import type { File } from './tracker'

vi.mock('./client')

interface TestCase {
  source: string
  expected: File[]
}

const data: TestCase[] = [
  {
    source: __dirname + '/__mocks__/files1.html',
    expected: [
      {
        name: "epzh uuueb ( qqtykjqkn nwobe epzh'h fgfya kqe)0614g.tn6",
        size: 6884147655,
      },
    ],
  },
  {
    source: __dirname + '/__mocks__/files2.html',
    expected: [
      {
        name: '65wwkkxsxu - munli tyoozqxl, otoqf geiru.tn6.wze',
        size: 871846,
      },
      {
        name: '0fyfui - tloynqd zib bvdhdsa.tn6.wze',
        size: 316609,
      },
      {
        name: 'zgzso6j - ubeycraeyd pixfuwd.tn6',
        size: 310537103,
      },
      {
        name: 'brkbix - umfek exsqzdddx rcbpl bsd jfnibxh get mosjar.tn6',
        size: 8096224666,
      },
      {
        name: 'rcbpl bsd.wze',
        size: 211909,
      },
    ],
  },
]

describe.each(data)('tracker $source', ({ source, expected }: TestCase) => {
  afterEach(() => {
    vi.clearAllMocks()
  })
  it('files', async () => {
    const file = await fs.readFile(source, 'utf-8')
    ;(client.send as Mock).mockResolvedValueOnce(file)

    const result = await files('12345')
    expect(result).toEqual(expected)
  })
})
