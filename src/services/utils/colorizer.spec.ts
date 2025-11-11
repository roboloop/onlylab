import { describe, expect, it } from 'vitest'
import { Colorizer } from '@/services/utils/colorizer'

const text = 'This line should be colorized.'

interface TestCase {
  desc: string
  input: TestCaseInput[]
  expected: string
}

interface TestCaseInput {
  words: string[]
  boundary: boolean
  color: string
}

const data: TestCase[] = [
  {
    desc: 'no words',
    input: [
      {
        words: [],
        boundary: false,
        color: 'red',
      },
    ],
    expected: 'This line should be colorized.',
  },
  {
    desc: 'boundary true',
    input: [
      {
        words: ['this', 'line'],
        boundary: false,
        color: 'red',
      },
    ],
    expected: '<span class="red">This</span> <span class="red">line</span> should be colorized.',
  },
  {
    desc: 'boundary false',
    input: [
      {
        words: ['thi', 'lin'],
        boundary: false,
        color: 'red',
      },
    ],
    expected: '<span class="red">Thi</span>s <span class="red">lin</span>e should be colorized.',
  },
  {
    desc: 'boundary true not all',
    input: [
      {
        words: ['thi', 'lin', 'should'],
        boundary: true,
        color: 'red',
      },
    ],
    expected: 'This line <span class="red">should</span> be colorized.',
  },
  {
    desc: 'color mix no intersection',
    input: [
      {
        words: ['thi', 'lin', 'should', 'foo'],
        boundary: true,
        color: 'red',
      },
      {
        words: ['be'],
        boundary: true,
        color: 'green',
      },
    ],
    expected: 'This line <span class="red">should</span> <span class="green">be</span> colorized.',
  },
  {
    desc: 'color mix with intersection',
    input: [
      {
        words: ['this', 'line'],
        boundary: false,
        color: 'red',
      },
      {
        words: ['line', 'should'],
        boundary: false,
        color: 'green',
      },
    ],
    expected:
      '<span class="red">This</span> <span class="green">line</span> <span class="green">should</span> be colorized.',
  },
]

describe('colorizer', () => {
  it.each(data)('colorize $desc', ({ input, expected }: TestCase) => {
    const colorizer = new Colorizer(text)

    input.forEach(({ words, boundary, color }: TestCaseInput): void => {
      colorizer.addWords(words, boundary, color)
    })

    const result = colorizer.build()

    expect(result).toEqual(expected)
  })

  it('none is reserved', () => {
    expect(() => {
      new Colorizer('').addWords([], false, 'none')
    }).toThrowError('none is reserved')
  })
})
