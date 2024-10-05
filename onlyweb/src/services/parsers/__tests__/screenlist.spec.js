import { describe, it, expect } from 'vitest'
import screenlist from '../screenlist.js'

const data = [
  {
    source:
      '48-73-3889-452022792.tn6 • 808.31 jr • 5885i081 • 64:06:46 • f638, 0747 pk/h, 41.64 hkf • swj (yh), 983 quuj, 29270 ao, 6 ia',
    expected: {
      name: '48-73-3889-452022792.tn6',
      size: '808.31 jr',
      quality: '5885i081',
      length: '64:06:46',
      extra: ['f638, 0747 pk/h, 41.64 hkf', 'swj (yh), 983 quuj, 29270 ao, 6 ia']
    }
  },
  {
    source:
      '6009-73-09_yqxinlori - bqonoisiurihiukhlnapllf.tn6 • 64:64:04 • 0.09 ty • 827i0614 • jrmd-6 • rqr • 41.221 asm • 6 736 pk/h • swj • 75.2 tis • 517 pk/h • 6 uorjlswi',
    expected: {
      name: '6009-73-09_yqxinlori - bqonoisiurihiukhlnapllf.tn6',
      size: '0.09 ty',
      quality: '827i0614',
      length: '64:64:04',
      extra: [
        'jrmd-6',
        'rqr',
        '41.221 asm',
        '6 736 pk/h',
        'swj',
        '75.2 tis',
        '517 pk/h',
        '6 uorjlswi'
      ]
    }
  },
  {
    source: '64:13:48 | 2,71 oq | 5885i081 | 11915_aghiuhfzxloogq.tn6',
    expected: {
      name: '11915_aghiuhfzxloogq.tn6',
      size: '2,71 oq',
      quality: '5885i081',
      length: '64:13:48',
      extra: []
    }
  },
  {
    source: 'fzf ifzibt 3889-61-63-190743.tn6 ● 64:41:09 ● 785,36 ty ● 5463i0614',
    expected: {
      name: 'fzf ifzibt 3889-61-63-190743.tn6',
      size: '785,36 ty',
      quality: '5463i0614',
      length: '64:41:09',
      extra: []
    }
  },
  {
    source:
      '[etzd.zzs] - 6869.67.13 - fiit j kpj jxpo wwdtjbv bzk rhoxf get csof svppl [asmy, rrthxt, fwrgsoy, wohzpnru].tn6 | 64:21:94 | 2,44 oq | 5463i0614',
    expected: {
      name: '[etzd.zzs] - 6869.67.13 - fiit j kpj jxpo wwdtjbv bzk rhoxf get csof svppl [asmy, rrthxt, fwrgsoy, wohzpnru].tn6',
      size: '2,44 oq',
      quality: '5463i0614',
      length: '64:21:94',
      extra: []
    }
  },
  // nx
  {
    source: 'gmjhketojwzp - nb aspdc aidkj vlhxoaj',
    expected: {
      name: 'gmjhketojwzp - nb aspdc aidkj vlhxoaj',
      size: undefined,
      quality: undefined,
      length: undefined,
      extra: []
    }
  },
  {
    source: 'kqick uuueb - itpebvojtzqsc.zzs.usf | 64:65:56 | 604.77 ty | 081i216',
    expected: {
      name: 'kqick uuueb - itpebvojtzqsc.zzs.usf',
      size: '604.77 ty',
      quality: '081i216',
      length: '64:65:56',
      extra: []
    }
  },
  {
    source:
      '5463i0614 | brocehawjeyxci - 8502 - tugtcl tkjf olwztlem 6.tn6 | 54 zzp 38 h | 2.92 tsl',
    expected: {
      name: 'brocehawjeyxci - 8502 - tugtcl tkjf olwztlem 6.tn6',
      size: '2.92 tsl',
      quality: '5463i0614',
      length: '54 zzp 38 h',
      extra: []
    }
  },
  {
    source:
      '5463i0614 | mpcjoheh oaxr - [ipzixdvvxd.zzs] - [8502] - qow444, essbpz oev xar tcsudkbn sk, mgk, bc - sjfgr ptuw.tn6 | 2 c 1 zzp | 1.56 tsl',
    expected: {
      name: 'mpcjoheh oaxr - [ipzixdvvxd.zzs] - [8502] - qow444, essbpz oev xar tcsudkbn sk, mgk, bc - sjfgr ptuw.tn6',
      size: '1.56 tsl',
      quality: '5463i0614',
      length: '2 c 1 zzp',
      extra: []
    }
  },
  {
    source: '64:64:41| 41,31 ty |5463i0614|67550870_pmqyc_8502-72-61_94-61.tn6',
    expected: {
      name: '67550870_pmqyc_8502-72-61_94-61.tn6',
      size: '41,31 ty',
      quality: '5463i0614',
      length: '64:64:41',
      extra: []
    }
  },
  {
    source:
      '5463i0614 | ndr smkd - [dt] - dy upbk c xhbgpcd imp whslhsw jb wgcpmur.tn6 | 63 zzp 66 h | 074 uib',
    expected: {
      name: 'ndr smkd - [dt] - dy upbk c xhbgpcd imp whslhsw jb wgcpmur.tn6',
      size: '074 uib',
      quality: '5463i0614',
      length: '63 zzp 66 h',
      extra: []
    }
  },
  {
    source:
      'iqipkgmdq - 6009.13.61 cbkxmdnl rzhdrcft 1941g.tn6 | 64:55:32 |  8,41 oq   | 7111i1941',
    expected: {
      name: 'iqipkgmdq - 6009.13.61 cbkxmdnl rzhdrcft 1941g.tn6',
      size: '8,41 oq',
      quality: '7111i1941',
      length: '64:55:32',
      extra: []
    }
  },
  {
    // gf rt snyntmf dkhg ｜
    source:
      '64:95:05 | 574,21 ty | 5463i0614 | g klcr c zvzmjhktny qm c oxlhgcmp uf lqdds scfng zib gztd wyc jdip zhmg sr dwh ｜ ohr ｜ dqymlqeyk.tn6',
    expected: {
      name: 'g klcr c zvzmjhktny qm c oxlhgcmp uf lqdds scfng zib gztd wyc jdip zhmg sr dwh ｜ ohr ｜ dqymlqeyk.tn6',
      size: '574,21 ty',
      quality: '5463i0614',
      length: '64:95:05',
      extra: []
    }
  }
]

describe.each(data)('$source -> $expected', ({ source, expected }) => {
  it('parse', () => {
    const result = screenlist.parse(source)
    expect(result).toStrictEqual(expected)
  })
})
