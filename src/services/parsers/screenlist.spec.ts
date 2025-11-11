import { describe, expect, it } from 'vitest'
import { parseScreenlist } from './screenlist'

interface TestCase {
  source: string
  expected: {
    name: string
    size?: string
    quality?: string
    length?: string
    extra: string[]
  }
}

const data: TestCase[] = [
  {
    source:
      '48-73-3889-452022792.tn6 • 808.31 MB • 1280x720 • 64:06:46 • f638, 0747 pk/s, 41.64 hkf • swj (yh), 983 quuj, 29270 ao, 6 ia',
    expected: {
      name: '48-73-3889-452022792.tn6',
      size: '808.31 MB',
      quality: '1280x720',
      length: '64:06:46',
      extra: ['f638, 0747 pk/s, 41.64 hkf', 'swj (yh), 983 quuj, 29270 ao, 6 ia'],
    },
  },
  {
    source:
      '6009-73-09_yqxinlori - bqonoisiurihiukhlnapllf.tn6 • 64:64:04 • 0.09 Mb • 864x1080 • jrmd-6 • rqr • 41.221 asm • 6 736 pk/s • swj • 75.2 tis • 517 pk/s • 6 uorjlswi',
    expected: {
      name: '6009-73-09_yqxinlori - bqonoisiurihiukhlnapllf.tn6',
      size: '0.09 Mb',
      quality: '864x1080',
      length: '64:64:04',
      extra: ['jrmd-6', 'rqr', '41.221 asm', '6 736 pk/s', 'swj', '75.2 tis', '517 pk/s', '6 uorjlswi'],
    },
  },
  {
    source: '64:13:48 | 2,71 Gb | 1280x720 | 11915_aghiuhfzxloogq.tn6',
    expected: {
      name: '11915_aghiuhfzxloogq.tn6',
      size: '2,71 Gb',
      quality: '1280x720',
      length: '64:13:48',
      extra: [],
    },
  },
  {
    source: 'fzf ifzibt 3889-61-63-190743.tn6 ● 64:41:09 ● 785,36 Mb ● 1920x1080',
    expected: {
      name: 'fzf ifzibt 3889-61-63-190743.tn6',
      size: '785,36 Mb',
      quality: '1920x1080',
      length: '64:41:09',
      extra: [],
    },
  },
  {
    source:
      '[etzd.zzs] - 6869.67.13 - fiit j kpj jxpo wwdtjbv bzk rhoxf get csof svppl [asmy, rrthxt, fwrgsoy, wohzpnru].tn6 | 64:21:94 | 2,44 Gb | 1920x1080',
    expected: {
      name: '[etzd.zzs] - 6869.67.13 - fiit j kpj jxpo wwdtjbv bzk rhoxf get csof svppl [asmy, rrthxt, fwrgsoy, wohzpnru].tn6',
      size: '2,44 Gb',
      quality: '1920x1080',
      length: '64:21:94',
      extra: [],
    },
  },
  // no
  {
    source: 'gmjhketojwzp - nb aspdc aidkj vlhxoaj',
    expected: {
      name: 'gmjhketojwzp - nb aspdc aidkj vlhxoaj',
      size: undefined,
      quality: undefined,
      length: undefined,
      extra: [],
    },
  },
  {
    source: 'kqick uuueb - itpebvojtzqsc.zzs.usf | 64:65:56 | 604.77 Mb | 720x576',
    expected: {
      name: 'kqick uuueb - itpebvojtzqsc.zzs.usf',
      size: '604.77 Mb',
      quality: '720x576',
      length: '64:65:56',
      extra: [],
    },
  },
  {
    source: '1920x1080 | brocehawjeyxci - 8502 - tugtcl tkjf olwztlem 6.tn6 | 54 min 38 s | 2.92 GiB',
    expected: {
      name: 'brocehawjeyxci - 8502 - tugtcl tkjf olwztlem 6.tn6',
      size: '2.92 GiB',
      quality: '1920x1080',
      length: '54 min 38 s',
      extra: [],
    },
  },
  {
    source:
      '1920x1080 | mpcjoheh oaxr - [ipzixdvvxd.zzs] - [8502] - qow444, essbpz oev xar tcsudkbn sk, mgk, bc - sjfgr ptuw.tn6 | 2 h 1 min | 1.56 GiB',
    expected: {
      name: 'mpcjoheh oaxr - [ipzixdvvxd.zzs] - [8502] - qow444, essbpz oev xar tcsudkbn sk, mgk, bc - sjfgr ptuw.tn6',
      size: '1.56 GiB',
      quality: '1920x1080',
      length: '2 h 1 min',
      extra: [],
    },
  },
  {
    source: '64:64:41| 41,31 Mb |1920x1080|67550870_pmqyc_8502-72-61_94-61.tn6',
    expected: {
      name: '67550870_pmqyc_8502-72-61_94-61.tn6',
      size: '41,31 Mb',
      quality: '1920x1080',
      length: '64:64:41',
      extra: [],
    },
  },
  {
    source: '1920x1080 | ndr smkd - [dt] - dy upbk c xhbgpcd imp whslhsw jb wgcpmur.tn6 | 63 min 66 s | 074 MiB',
    expected: {
      name: 'ndr smkd - [dt] - dy upbk c xhbgpcd imp whslhsw jb wgcpmur.tn6',
      size: '074 MiB',
      quality: '1920x1080',
      length: '63 min 66 s',
      extra: [],
    },
  },
  {
    source: 'iqipkgmdq - 6009.13.61 cbkxmdnl rzhdrcft 1941g.tn6 | 64:55:32 |  8,41 Gb   | 3840x2160',
    expected: {
      name: 'iqipkgmdq - 6009.13.61 cbkxmdnl rzhdrcft 1941g.tn6',
      size: '8,41 Gb',
      quality: '3840x2160',
      length: '64:55:32',
      extra: [],
    },
  },
  {
    // it is unicode pipe ｜
    source:
      '64:95:05 | 574,21 Mb | 1920x1080 | g klcr c zvzmjhktny qm c oxlhgcmp uf lqdds scfng zib gztd wyc jdip zhmg sr dwh ｜ ohr ｜ dqymlqeyk.tn6',
    expected: {
      name: 'g klcr c zvzmjhktny qm c oxlhgcmp uf lqdds scfng zib gztd wyc jdip zhmg sr dwh ｜ ohr ｜ dqymlqeyk.tn6',
      size: '574,21 Mb',
      quality: '1920x1080',
      length: '64:95:05',
      extra: [],
    },
  },
  {
    // × as a quality separator
    source: 'lgvipzyou tirmtw (63.13.3451) [sbfkynkowzfbmwi.zzs].usf | 64:65:93 | 2.65 Gb | 5463×0614',
    expected: {
      name: 'lgvipzyou tirmtw (63.13.3451) [sbfkynkowzfbmwi.zzs].usf',
      size: '2.65 Gb',
      quality: '5463×0614',
      length: '64:65:93',
      extra: [],
    },
  },
]

describe.each(data)('$source -> $expected', ({ source, expected }: TestCase) => {
  it('parse', () => {
    const result = parseScreenlist(source)
    expect(result).toStrictEqual(expected)
  })
})
