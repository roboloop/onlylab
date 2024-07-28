import { describe, it, expect } from 'vitest'
import { parseStudio } from '../studio'
import data from './input'

describe.each(data)('$source -> $expected.studios', ({ source, expected }) => {
  it('test', () => {
    const result = parseStudio(source)
    expect(expected.studios).toStrictEqual(result)
  })
})
