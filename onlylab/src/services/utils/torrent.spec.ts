import fs from 'node:fs/promises'
import { describe, expect, it } from 'vitest'
import { extractName } from '@/services/utils/torrent'

interface TestCase {
  source: string
  expected: string
}

const data: TestCase[] = [
  {
    source: import.meta.dirname + '/__mocks__/torrent1.torrent',
    expected: 'lqhl olvw - gfhnbpwq pn eahyvudxsms',
  },
  {
    source: import.meta.dirname + '/__mocks__/torrent2.torrent',
    expected: 'aghidaeafx_pil_pil723_pil723_h26_tn6_0614.tn6',
  },
]

// @vitest-environment node
describe.each(data)('torrent', ({ source, expected }: TestCase) => {
  it('extractName', async () => {
    // TODO: use jsdom env
    const file = await fs.readFile(source)
    const uint8Array = new Uint8Array(file)

    const blob = new Blob([uint8Array], { type: 'application/octet-stream' })

    const result = await extractName(blob)
    expect(result).toEqual(expected)
  })
})
