import { describe, expect, it } from 'vitest'
import data from './__mocks__/input'
import { parseTitle } from './title'

import type { TestCase } from './__mocks__/input'

describe.each(data)('$source -> $expected.originalTitle', ({ source, expected }: TestCase) => {
  it('parseTitle', () => {
    const result = parseTitle(source)
    expect(result).toStrictEqual(expected.originalTitle)
  })
})
