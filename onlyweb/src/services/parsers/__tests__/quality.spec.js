import { describe, it, expect } from 'vitest'
import { parseQuality } from '../quality'
import data from './input'

describe.each(data)('$source -> $expected.quality', ({ source, expected }) => {
  it('test', () => {
    const result = parseQuality(source)
    expect(result).toStrictEqual(expected.quality)
  })
})
