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
  {
    source: 'tne yktd & bavggc tvopq. rzr-gs epjlg (8502-26-79)',
    expected: ['tne yktd', 'bavggc tvopq']
  },
  {
    source: "vhbox rastwh / vhbox zhrod'h plpnjau zib asmy wuakmfvipc (904 xuirzud)",
    expected: ['vhbox rastwh']
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
  },
  {
    source: 'kcpubg (cpe kcpubg gajckkb) - xfqnxbpj kcpubg rsoj get vtriitm zgexa kqe weyjsq gs',
    expected: ['kcpubg', 'kcpubg gajckkb']
  },
  {
    source:
      'duhgn(cpe duhgn j), wohynzs awogt (cpe zznrkt mxlukw, fwztqd) - g csir, rcrl bzk jfrs lcd pmco kcpubg',
    expected: ['duhgn', 'duhgn j', 'wohynzs awogt', 'zznrkt mxlukw', 'fwztqd']
  },
  {
    source:
      'sampu amivcv (cpe hlivrjtn fhjocm), lhydi uwwy, qkqhd shhr - jtqnylb lrmpnjwg tt. 2 / nzmcgimfqbf czlupysc (pnblu 2)',
    expected: ['sampu amivcv', 'hlivrjtn fhjocm', 'lhydi uwwy', 'qkqhd shhr']
  },
  {
    source:
      'qjafgd (ovb: qjafgd r, qjafgd zeqtpn, qjafgd rxlsrsz, vxviq, tjmsgb ouj) (13.73.2560 l.)',
    expected: ['qjafgd', 'qjafgd r', 'qjafgd zeqtpn', 'qjafgd rxlsrsz', 'vxviq', 'tjmsgb ouj']
  },
  {
    source: '8502-13-31 navwji hlpa - rzglbnycao xqz 3376',
    expected: ['navwji hlpa']
  },
  {
    source: 'hgab fthi – vgu icrgox tasw jalcr dy (8502-13-67)',
    expected: ['hgab fthi']
  },
  {
    source: 'hgab fthi — vgu icrgox tasw jalcr dy (8502-13-67)',
    expected: ['hgab fthi']
  }
]

describe.each(data)('$source -> $expected', ({ source, expected }) => {
  it('test', () => {
    const result = parseName(source)
    expect(expected).toStrictEqual(result)
  })
})
