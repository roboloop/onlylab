import { describe, expect, it } from 'vitest'
import data from './__mocks__/input'
import { parseYear } from './year'

import type { TestCase } from './__mocks__/input'

describe.each(data)('$source -> $expected.year', ({ source, expected }: TestCase) => {
  it('parseYear', () => {
    const result = parseYear(source)
    expect(result).toStrictEqual(expected.year)
  })
})
