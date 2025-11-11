import { describe, expect, it } from 'vitest'
import { parseLength } from '@/services/parsers/length'

interface TestCase {
  source: string
  expected?: number
}

const data: TestCase[] = [
  {
    source: '',
    expected: undefined,
  },
  {
    source: '123',
    expected: undefined,
  },

  {
    source: '00:00:00',
    expected: 0,
  },
  {
    source: '00:02:03',
    expected: 123,
  },
  {
    source: '01:02:03',
    expected: 3723,
  },

  {
    source: '3 s',
    expected: 3,
  },
  {
    source: '2 min 3 s',
    expected: 123,
  },
  {
    source: '1 h 2 min',
    expected: 3720,
  },
  {
    source: '1 h 2 min 3 s',
    expected: 3723,
  },
  {
    source: '1 h 3 s',
    expected: 3603,
  },
]

describe.each(data)('length $source', ({ source, expected }: TestCase) => {
  it('parseLength', () => {
    const result = parseLength(source)
    expect(result).equals(expected)
  })
})
