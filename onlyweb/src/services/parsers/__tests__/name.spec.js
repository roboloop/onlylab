import { describe, it, expect } from 'vitest'
import { parseName } from '../name'

const data = [
  {
    source: 'wfxer ctxlmtl ( 1 pxwmqi xua wlcbfr wfxer yw1)',
    expected: ['wfxer ctxlmtl']
  },
  {
    source: 'fmjmnlj zwsnw - g oork vafn fjrjdeo',
    expected: ['fmjmnlj zwsnw']
  },
  {
    source: 'gpwyb tiag — hafeqlkjiouq ydpjdusl gpwyb tiag dvbcwh bfgzztpn bc',
    expected: ['gpwyb tiag']
  },
  {
    source: 'uinheocrz e - j ozoi nqqjxqz uzfd (09.09.2691)',
    expected: ['uinheocrz e']
  },
  {
    source: 'otaaqaydl xkjhn & ixinn srri - otaaqaydl xkjhn fwdagriwd zamzn fgdf bfgzztpn rcrl',
    expected: ['otaaqaydl xkjhn', 'ixinn srri']
  },
  {
    source: 'spppfj qfkoq bzk zxpz qfkoq - nuaalki twx nqt fonv',
    expected: ['spppfj qfkoq', 'zxpz qfkoq']
  },
  {
    source: 'hmwil ydmjw, xjvvl oju (ayflfv otfeco)',
    expected: ['hmwil ydmjw', 'xjvvl oju']
  },
  {
    source: 'otaaqaydl xkjhn, sdxqm rntqhfj - 2pk lecw feh thai flxq - sdxqm rntqhfj!',
    expected: ['otaaqaydl xkjhn', 'sdxqm rntqhfj']
  },
  {
    source:
      'zqnc duxdd, ddwuge yuzhli, sxenbs kbtobwomxq - vgu kpj etzd jbepbhz 6, j lkefmhp 6-utvb rt cfmageg xua reytdg rdkr akyxs zqnc duxdd, ddwuge yuzhli, zib sxenbs kbtobwomxq me lee lftrwd sr eftc (30196) ',
    expected: ['zqnc duxdd', 'ddwuge yuzhli', 'sxenbs kbtobwomxq']
  },
  {
    source: "jqdl kaskc cpe jqdl kaskc96 zib mtyd n'keak - gnyfqdpys flxq hbvaq ofir",
    expected: ['jqdl kaskc', 'jqdl kaskc96', "mtyd n'keak"]
  },
  {
    source:
      "tjsnde iamy jvoyfa - tloynqd keue tjsnde iamy zokib zib wcolf wyc nms'h bgjvxb hodlhslb",
    expected: ['tjsnde iamy jvoyfa']
  },
  {
    source: 'bfvpf lij nuhy, lexv koepxl (6 tmack-wnt zqeyb)',
    expected: ['bfvpf lij nuhy', 'lexv koepxl']
  },

  // kmf-fdbduzwb
  {
    source: 'zpmjvrmje49 - meywz nv ugjfrqi mzbi xoc tuflhtwwh',
    expected: ['zpmjvrmje49']
  },
  {
    source: 'blwil (46) - umfek blwil rt c pha pyvi',
    expected: ['blwil']
  },
  {
    source: 'eivf (ffmoa & aihdg wdvy)',
    expected: ['eivf']
  },
  {
    source: 'baryyssygngkl',
    expected: ['baryyssygngkl']
  }
]

describe.each(data)('$source -> $expected', ({ source, expected }) => {
  it('test', () => {
    const result = parseName(source)
    expect(expected).toStrictEqual(result)
  })
})
