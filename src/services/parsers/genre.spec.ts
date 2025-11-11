import { describe, expect, it } from 'vitest'
import data from './__mocks__/input'
import { parseGenre } from './genre'

import type { TestCase } from './__mocks__/input'

describe.each(data)('$source -> $expected.genres', ({ source, expected }: TestCase) => {
  it('parseGenre', () => {
    const result = parseGenre(source)
    expect(result).toStrictEqual(expected.genres)
  })
})
