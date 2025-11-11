export interface TestCase {
  source: string
  expected: {
    genres: string[]
    originalTitle: string
    quality: string
    studios: string[]
    year: string
  }
}

export default [
  {
    source:
      '[ipzixdvvxd.zzs] aihdg 41 ruwfd hix qjafgd eywi gsfiwkzz mzbi wzp avxi pu6696 / 65.67.2019 [pjzz, asmy, omdu, kpj pwan, bstyfrz, 1080p]',
    expected: {
      genres: ['pjzz', 'asmy', 'omdu', 'kpj pwan', 'bstyfrz'],
      originalTitle: 'aihdg 41 ruwfd hix qjafgd eywi gsfiwkzz mzbi wzp avxi pu6696 / 65.67.2019',
      quality: '1080p',
      studios: ['ipzixdvvxd.zzs'],
      year: '2019',
    },
  },
  {
    source:
      "[spkwkbsy.zzs] xlrlk gpxxqc - xlrlk'm phxan asmy (72.73.2019) [2019 l., asmy, sygbhz, wdvy, ygc ixa, pgcjavlpl, gvt, kpj jxpo, etpfp, bfgzztpn, fnoohek pwan, whdhjc, ydpjdusl, yvwlc pwmlc, ohjjvqc, ofespjt, 720p]",
    expected: {
      genres: [
        'asmy',
        'sygbhz',
        'wdvy',
        'ygc ixa',
        'pgcjavlpl',
        'gvt',
        'kpj jxpo',
        'etpfp',
        'bfgzztpn',
        'fnoohek pwan',
        'whdhjc',
        'ydpjdusl',
        'yvwlc pwmlc',
        'ohjjvqc',
        'ofespjt',
      ],
      originalTitle: "xlrlk gpxxqc - xlrlk'm phxan asmy (72.73.2019)",
      quality: '720p',
      studios: ['spkwkbsy.zzs'],
      year: '2019',
    },
  },
  {
    source:
      '[ngpopjlkdvrqto.zzs / kpttvzhr.zzs] trytpu yvdsy (zxdwv-yaoudyjb twx fonv / 23.09.2018) [2018, kqe rkdrvji,kpj kqe,kpj pwan rkdrvji,egzwsk,wihzbyn (cmj),rdpi uiai,mdjwi,lffk,dijl pwan,qboptdbm,rrthxt,oewbom,vvtvzyffcm, 4k]',
    expected: {
      genres: [
        'kqe rkdrvji',
        'kpj kqe',
        'kpj pwan rkdrvji',
        'egzwsk',
        'wihzbyn (cmj)',
        'rdpi uiai',
        'mdjwi',
        'lffk',
        'dijl pwan',
        'qboptdbm',
        'rrthxt',
        'oewbom',
        'vvtvzyffcm',
      ],
      originalTitle: 'trytpu yvdsy (zxdwv-yaoudyjb twx fonv / 23.09.2018)',
      quality: '2160p',
      studios: ['ngpopjlkdvrqto.zzs', 'kpttvzhr.zzs'],
      year: '2018',
    },
  },
  {
    source: "[spkwkbsy.zzs] tvqgbcny ttgk(tvqgbcny'h asmy kdet )[2019 l., etpfp asmy bfgzztpn, 720p]",
    expected: {
      genres: ['etpfp', 'asmy', 'bfgzztpn'],
      originalTitle: "tvqgbcny ttgk(tvqgbcny'h asmy kdet )",
      quality: '720p',
      studios: ['spkwkbsy.zzs'],
      year: '2019',
    },
  },
  {
    source:
      "[tptqgf'h cmj euxgdq jfoh / komvipfvrfcc.zzs / zycyi6hvdb.zzs] pdcbd bjqgqnf (phdribxh nv aukoeajt hfkemq ekczlm pzktj feh tnbqrq olausgxdjs) [2020, pha pfin, qekhzyl, byk, vwvbq, bsse., 1080p]",
    expected: {
      genres: ['pha pfin', 'qekhzyl', 'byk', 'vwvbq', 'bsse'],
      originalTitle: 'pdcbd bjqgqnf (phdribxh nv aukoeajt hfkemq ekczlm pzktj feh tnbqrq olausgxdjs)',
      quality: '1080p',
      studios: ["tptqgf'h cmj euxgdq jfoh", 'komvipfvrfcc.zzs', 'zycyi6hvdb.zzs'],
      year: '2020',
    },
  },
  {
    source:
      '[zuqluub.zzs / sblcnlxerhtk.zzs] kaskc nwcip - kaskc jtds dy j pctsqd (65.72.2020 l., asmy, lcd feh, ohjjvqc, 1080p]',
    expected: {
      genres: ['asmy', 'lcd feh', 'ohjjvqc'],
      originalTitle: 'kaskc nwcip - kaskc jtds dy j pctsqd',
      quality: '1080p',
      studios: ['zuqluub.zzs', 'sblcnlxerhtk.zzs'],
      year: '2020',
    },
  },
  {
    source:
      '[swajvyh.zzs] qrgl mzon (mzon, mzon, mpusvlj wy get joajbxsp / 41.31.2022) [2022 l., asmy, hasgvnl pwmlc, mm, 4K, 1920p] [Oculus Rift / Quest 2 / Vive]',
    expected: {
      genres: ['asmy', 'hasgvnl pwmlc', 'mm'],
      originalTitle: 'qrgl mzon (mzon, mzon, mpusvlj wy get joajbxsp / 41.31.2022)',
      quality: '1920p',
      studios: ['swajvyh.zzs'],
      year: '2022',
    },
  },
  {
    source: 'vjotwdtzac dofocnn kuzermx prnylyz q dfti [2023, kdoqxmt, asmy, lcd feh, 1920p]',
    expected: {
      genres: ['kdoqxmt', 'asmy', 'lcd feh'],
      originalTitle: 'vjotwdtzac dofocnn kuzermx prnylyz q dfti',
      quality: '1920p',
      studios: [],
      year: '2023',
    },
  },
  {
    source:
      '[cnsmxmp.zzs / rutkezkgcwf.zzs]rqwxy ffom ( vgu kktzpaup asmy jcbn) [2024 l., etpfp, a.n.m., bfgzztpn ,pysjbvm, lcd feh, asmy , sn 1080p]',
    expected: {
      genres: ['etpfp', 'a.n.m.', 'bfgzztpn', 'pysjbvm', 'lcd feh', 'asmy', 'sn'],
      originalTitle: 'rqwxy ffom ( vgu kktzpaup asmy jcbn)',
      quality: '1080p',
      studios: ['cnsmxmp.zzs', 'rutkezkgcwf.zzs'],
      year: '2024',
    },
  },
  // ttrgzaup
  {
    source:
      '[wlaqjgsu.zzs] oftgpecc (toqkcsnqu, daomck uzv, xfejz uzv) asmy evwitqnzczjw [uncen] [2024 l., meshrfrevwnq, abknh, xhit, asmy, 1080p]',
    expected: {
      genres: ['meshrfrevwnq', 'abknh', 'xhit', 'asmy'],
      originalTitle: 'oftgpecc (toqkcsnqu, daomck uzv, xfejz uzv) asmy evwitqnzczjw',
      quality: '1080p',
      studios: ['wlaqjgsu.zzs'],
      year: '2024',
    },
  },
  {
    source:
      "uasgh - hjzlts'h sdontoxnuefg. (mgkmvntu iormf) [jj-13] [uncen] [2024 l., asmy, txv, gnyfqdpys, docwjg, 1080p]",
    expected: {
      genres: ['asmy', 'txv', 'gnyfqdpys', 'docwjg'],
      originalTitle: "uasgh - hjzlts'h sdontoxnuefg. (mgkmvntu iormf)",
      quality: '1080p',
      studios: [],
      year: '2024',
    },
  },
  {
    source: '[ymtalzneao.zzs] tipf gbha - pwmlc wughtl grafptt [2023-73-09, asmy, egzwsk, ohjjvqc, 1080p, SiteRip]',
    expected: {
      genres: ['asmy', 'egzwsk', 'ohjjvqc'],
      originalTitle: 'tipf gbha - pwmlc wughtl grafptt',
      quality: '1080p',
      studios: ['ymtalzneao.zzs'],
      year: '2023',
    },
  },
  {
    source:
      'mpcjoheh oaxr - rjdfuy iiqkd yekqjiwp evwt (41 xuirzud) uzot (mpcjoheh umnn, ukrcj) (r2.2 [2024-16-93] znsvyv + 09) [2023 - 2024, nb unfulj asmy, bc, pvp, sk]',
    expected: {
      genres: ['nb unfulj asmy', 'bc', 'pvp', 'sk'],
      // jzzb: kyjr thkugyv  (r2.2 [2024-16-93] znsvyv + 09) uf lee pie
      originalTitle: 'mpcjoheh oaxr - rjdfuy iiqkd yekqjiwp evwt (41 xuirzud) uzot (mpcjoheh umnn, ukrcj)',
      quality: '',
      studios: [],
      year: '2023 - 2024',
    },
  },
  {
    source: '[rzglbnycao.zzs] ssxnxh htdeq (#6629) [2024-61-94, abknh, meshrfrevwnq, oxwndg, xhit, 1080p]',
    expected: {
      genres: ['abknh', 'meshrfrevwnq', 'oxwndg', 'xhit'],
      originalTitle: 'ssxnxh htdeq (#6629)',
      quality: '1080p',
      studios: ['rzglbnycao.zzs'],
      year: '2024',
    },
  },
] as TestCase[]
