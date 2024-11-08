import { describe, expect, it } from 'vitest'
import data from './__mocks__/input'
import { parseStudio } from './studio'

import type { TestCase } from './__mocks__/input'

describe.each(data)('$source -> $expected.studios', ({ source, expected }: TestCase) => {
  it('parseStudio', () => {
    const result = parseStudio(source)
    expect(result).toStrictEqual(expected.studios)
  })
})
