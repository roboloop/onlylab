import { describe, it, expect } from 'vitest'
import { parseTitle } from '../title.js'
import data from './input'

describe.each(data)('$source -> $expected.originalTitle', ({ source, expected }) => {
  it('test', () => {
    const result = parseTitle(source)
    expect(expected.originalTitle).toStrictEqual(result)
  })
})
