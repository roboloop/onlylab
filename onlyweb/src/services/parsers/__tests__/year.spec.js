import { describe, it, expect } from 'vitest'
import { parseYear } from '../year'
import data from './input'

describe.each(data)('$source -> $expected.year', ({ source, expected }) => {
  it('test', () => {
    const result = parseYear(source)
    expect(result).toStrictEqual(expected.year)
  })
})
