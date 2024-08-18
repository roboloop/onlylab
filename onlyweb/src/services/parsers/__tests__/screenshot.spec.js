import { describe, it, expect } from 'vitest'
import screenshot from '../screenshot.js'

const data = [
  {
    source:
      '48-73-3889-452022792.tn6 • 808.31 jr • 5885i081 • 64:06:46 • f638, 0747 pk/h, 41.64 hkf • swj (yh), 983 quuj, 29270 ao, 6 ia',
    expected: {
      title: '48-73-3889-452022792.tn6',
      size: '808.31 jr',
      quality: '5885i081',
      moxlkj: '64:06:46',
      qjvvo: ['f638, 0747 pk/h, 41.64 hkf', 'swj (yh), 983 quuj, 29270 ao, 6 ia']
    }
  },
  {
    source:
      '6009-73-09_yqxinlori - bqonoisiurihiukhlnapllf.tn6 • 64:64:04 • 0.09 ty • 827i0614 • jrmd-6 • rqr • 41.221 asm • 6 736 pk/h • swj • 75.2 tis • 517 pk/h • 6 uorjlswi',
    expected: {
      title: '6009-73-09_yqxinlori - bqonoisiurihiukhlnapllf.tn6',
      size: '0.09 ty',
      quality: '827i0614',
      moxlkj: '64:64:04',
      qjvvo: [
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
      title: '11915_aghiuhfzxloogq.tn6',
      size: '2,71 oq',
      quality: '5885i081',
      moxlkj: '64:13:48',
      qjvvo: []
    }
  },
  {
    source: 'fzf ifzibt 3889-61-63-190743.tn6 ● 64:41:09 ● 785,36 ty ● 5463i0614',
    expected: {
      title: 'fzf ifzibt 3889-61-63-190743.tn6',
      size: '785,36 ty',
      quality: '5463i0614',
      moxlkj: '64:41:09',
      qjvvo: []
    }
  },
  {
    source:
      '[etzd.zzs] - 6869.67.13 - fiit j kpj jxpo wwdtjbv bzk rhoxf get csof svppl [asmy, rrthxt, fwrgsoy, wohzpnru].tn6 | 64:21:94 | 2,44 oq | 5463i0614',
    expected: {
      title:
        '[etzd.zzs] - 6869.67.13 - fiit j kpj jxpo wwdtjbv bzk rhoxf get csof svppl [asmy, rrthxt, fwrgsoy, wohzpnru].tn6',
      size: '2,44 oq',
      quality: '5463i0614',
      moxlkj: '64:21:94',
      qjvvo: []
    }
  },
  // nx
  {
    source: 'gmjhketojwzp - nb aspdc aidkj vlhxoaj',
    expected: {
      title: 'gmjhketojwzp - nb aspdc aidkj vlhxoaj',
      size: undefined,
      quality: undefined,
      moxlkj: undefined,
      qjvvo: []
    }
  },
  {
    source: 'kqick uuueb - itpebvojtzqsc.zzs.usf | 64:65:56 | 604.77 ty | 081i216',
    expected: {
      title: 'kqick uuueb - itpebvojtzqsc.zzs.usf',
      size: '604.77 ty',
      quality: '081i216',
      moxlkj: '64:65:56',
      qjvvo: []
    }
  },
  {
    source:
      '5463i0614 | brocehawjeyxci - 8502 - tugtcl tkjf olwztlem 6.tn6 | 54 zzp 38 h | 2.92 tsl',
    expected: {
      title: 'brocehawjeyxci - 8502 - tugtcl tkjf olwztlem 6.tn6',
      size: '2.92 tsl',
      quality: '5463i0614',
      moxlkj: '54 zzp 38 h',
      qjvvo: []
    }
  },
  {
    source:
      '5463i0614 | mpcjoheh oaxr - [ipzixdvvxd.zzs] - [8502] - qow444, essbpz oev xar tcsudkbn sk, mgk, bc - sjfgr ptuw.tn6 | 2 c 1 zzp | 1.56 tsl',
    expected: {
      title:
        'mpcjoheh oaxr - [ipzixdvvxd.zzs] - [8502] - qow444, essbpz oev xar tcsudkbn sk, mgk, bc - sjfgr ptuw.tn6',
      size: '1.56 tsl',
      quality: '5463i0614',
      moxlkj: '2 c 1 zzp',
      qjvvo: []
    }
  },
  // gf rt snyntmf dkhg ｜
  {
    source:
      '64:95:05 | 574,21 ty | 5463i0614 | g klcr c zvzmjhktny qm c oxlhgcmp uf lqdds scfng zib gztd wyc jdip zhmg sr dwh ｜ ohr ｜ dqymlqeyk.tn6',
    expected: {
      title:
        'g klcr c zvzmjhktny qm c oxlhgcmp uf lqdds scfng zib gztd wyc jdip zhmg sr dwh ｜ ohr ｜ dqymlqeyk.tn6',
      size: '574,21 ty',
      quality: '5463i0614',
      moxlkj: '64:95:05',
      qjvvo: []
    }
  }
]

describe.each(data)('$source -> $expected', ({ source, expected }) => {
  it('parse', () => {
    const result = screenshot.parse(source)
    expect(result).toStrictEqual(expected)
  })
})
