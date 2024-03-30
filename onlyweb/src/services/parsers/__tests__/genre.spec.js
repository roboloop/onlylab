import { describe, it, expect } from 'vitest'
import { parseGenre } from '../genre'
import data from './input'

describe.each(data)('$source -> $expected.quality', ({ source, expected }) => {
  it('test', () => {
    const result = parseGenre(source)
    expect(expected.genres).toStrictEqual(result)
  })
})
