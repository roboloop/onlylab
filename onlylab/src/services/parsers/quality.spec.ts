import { describe, expect, it } from 'vitest'
import data from './__mocks__/input'
import { parseQuality } from './quality'

import type { TestCase } from './__mocks__/input'

describe.each(data)('$source -> $expected.quality', ({ source, expected }: TestCase) => {
  it('parseQuality', () => {
    const result = parseQuality(source)
    expect(result).toStrictEqual(expected.quality)
  })
})
