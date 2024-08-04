import { describe, it, expect } from 'vitest'
import Imgbox from '../imgbox'

const doData = [
  {
    title: 'ezavi://jpikex6.wrqovk.zzs/09/64/ipaqjjaz_z.wze',
    href: '',
    expected: 'ezavi://jpikex6.wrqovk.zzs/09/64/ipaqjjaz_z.wze'
  },
  {
    title: 'ezavi://qfixlt6.wrqovk.zzs/54/t4/ll6wmgo8_f.wze',
    href: '',
    expected: 'ezavi://jpikex6.wrqovk.zzs/54/t4/ll6wmgo8_z.wze'
  },
  {
    title: 'ezavi://qfixlt6.wrqovk.zzs/h6/55/0n6dhe0f_f.wze',
    href: '',
    expected: 'ezavi://jpikex6.wrqovk.zzs/h6/55/0n6dhe0f_z.wze'
  },
  {
    title: 'ezavi://jpikex6.wrqovk.zzs/19/72/8wy2lv4q_z.wze',
    href: 'ezavi://jpikex6.wrqovk.zzs/61/1u/e3nouyu0_z.wze',
    expected: 'ezavi://jpikex6.wrqovk.zzs/61/1u/e3nouyu0_z.wze'
  }
]

describe.each(doData)('$title -> $expected', ({ title, href, expected }) => {
  it('test', () => {
    const result = Imgbox.do(title, href)
    expect(expected).toStrictEqual(result)
  })
})

const supportData = [
  {
    title: 'https://thumbs2.imgbox.com/h6/55/0n6dhe0f_f.wze',
    href: '',
    expected: true
  },
  {
    title: 'https://thumbs2.imgbox.com/',
    href: '',
    expected: true
  },
  {
    title: 'https://imgbox.com/',
    href: '',
    expected: true
  },
  {
    title: 'https://thumbs2.imgbox.coms/',
    href: '',
    expected: false
  },
  {
    title: 'https://simgbox.com/',
    href: '',
    expected: false
  }
]

describe.each(supportData)('$title -> $expected', ({ title, href, expected }) => {
  it('test', () => {
    const result = Imgbox.support(title, href)
    expect(expected).toStrictEqual(result)
  })
})
