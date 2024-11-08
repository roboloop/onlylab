import { describe, expect, it } from 'vitest'
import { interpolate } from '@/services/utils/strings'

import type { Placeholder } from '@/services/utils/strings'

interface TestCase {
  template: string
  placeholder: Placeholder
  expected: string
}

const data: TestCase[] = [
  {
    template: '/path/to/%Y-%M-%D/%f/%q/%S_%A/',
    placeholder: {
      date: new Date('2024-01-09'),
      forum: "tgtuuoea 8502 (gf biaaw) / nepjzkh'h 8502 (gf biaaw)",
      quality: '0614g',
      studios: ['rdssofpd.zzs', 'nkdcpjx.zzs'],
      actresses: ['lqhl olvw'],
    },
    expected: '/path/to/2024-01-09/tgtuuoea 8502 (gf biaaw)/0614g/nkdcpjx.zzs,rdssofpd.zzs_lqhl olvw/',
  },
  {
    template: '/path/to/%S/%A/',
    placeholder: {
      date: new Date(),
      forum: '',
      quality: '',
      studios: [],
      actresses: [],
    },
    expected: '/path/to/no studios/no actresses/',
  },
]

describe.each(data)('strings', ({ template, placeholder, expected }: TestCase) => {
  it('interpolate', () => {
    const result = interpolate(template, placeholder)
    expect(result).toEqual(expected)
  })
})
