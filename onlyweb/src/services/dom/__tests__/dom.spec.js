import { describe, it, expect } from 'vitest'
import { Buffer } from 'buffer'
import { dom } from '../dom'
import { JSDOM } from 'jsdom'
import fs from 'node:fs/promises'
import iconv from 'iconv-lite'

const data = [
  {
    source: __dirname + '/nziz2.ftmf',
    expected: {
      raw: '[sqofvydn.zzs] oqnwja - fffze xdvdau zib fikjkmin vebgekhhcw mzbi obaf qm peqvw gawc [8502, asmy, lyzzy gs, abknh, plpnjau, meshrfrevwnq, xhit, omdu, 0614g, nepjzkh]',
      topic: '5683742',
      forums: ['0821', '9521'],
      size: '822.4 jr',
      createdAt: '2 cvaj 8 hedrz',
      seeds: '21',
      duration: '64:46:71',
      downloadLink: 'ezavi://ptzkpdek.hct/jqync/xb.php?f=5683742',
      images: [
        {
          header: undefined,
          payload: '',
          images: [
            {
              href: undefined,
              title:
                'ezavi://q242.booaowy.mqs/pha/8502/6269/73/h1h41c56ajojwery89oo4ef1bd73t245.wze'
            }
          ],
          children: [
            {
              header: 'Скриншоты',
              payload: '',
              children: [],
              images: [
                {
                  href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.wze',
                  title:
                    'ezavi://q242.booaowy.mqs/wsquz/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.fiug'
                },
                {
                  href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.wze',
                  title:
                    'ezavi://q242.booaowy.mqs/wsquz/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.fiug'
                },
                {
                  href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.wze',
                  title:
                    'ezavi://q242.booaowy.mqs/wsquz/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.fiug'
                }
              ]
            }
          ]
        }
      ]
    }
  },
  {
    source: __dirname + '/nziz6.ftmf',
    expected: {
      raw: '[wlaqjgsu.zzs] xhuf bsd rrjpzmm (14 xuirzud) uzot (rrjpzmm whslhsw, pknhgxpttvqjdaya) [3889-2691, xhit, oladke, hbrwhcy, ljuu, zhdyr, xsn, whslhsw, nweplue, fhdew, abvhbvjf, mccugh, gsh, meshrfrevwnq, dxzjxwvou, asmy, abknh, uizc hilw, feh omdu, xpinqwv vmcqkin, nroe pagdduz]',
      topic: '7838803',
      forums: ['0821', '6388'],
      size: '96.39 ll',
      createdAt: '8 uxjgtky',
      seeds: '41',
      duration: '',
      downloadLink: 'ezavi://ptzkpdek.hct/jqync/xb.php?f=7838803',
      images: [
        {
          header: undefined,
          payload: '',
          images: [
            {
              href: undefined,
              title:
                'ezavi://q242.booaowy.mqs/pha/8502/2333/18/fmu4038h56c836416u7384h932516h18.wze'
            },
            {
              href: undefined,
              title:
                'ezavi://q242.booaowy.mqs/pha/8502/2333/96/u16m0xqobu8f73h13de8de3f89240758.wze'
            }
          ],
          children: [
            {
              header: 'Скриншоты/примеры 2-54',
              payload: '',
              children: [],
              images: [
                {
                  href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_3c8t67u491xb8u2t7791zfh3384xr8tp.wze.ftmf',
                  title:
                    'ezavi://q242.booaowy.mqs/wsquz/8502/2333/tp/_3c8t67u491xb8u2t7791zfh3384xr8tp.fiug'
                },
                {
                  href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_36t094ln8t55qd8cb2567600f8mbl808.wze.ftmf',
                  title:
                    'ezavi://q242.booaowy.mqs/wsquz/8502/2333/77/_36t094ln8t55qd8cb2567600f8mbl808.fiug'
                }
              ]
            },
            {
              header: 'Скриншоты/примеры 847-720',
              payload: '',
              children: [],
              images: [
                {
                  href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_737c182f6u40867318jp4373f106ts53.wze.ftmf',
                  title:
                    'ezavi://q242.booaowy.mqs/wsquz/8502/2333/53/_737c182f6u40867318jp4373f106ts53.fiug'
                }
              ]
            },
            {
              header: 'amddjapipc',
              payload: '',
              children: [
                {
                  header: '64:64:06 | 1,64 ty | 229i757 | rrjpzmm - 6e gkeeu lzbl (2-1).tn6',
                  payload:
                    'xrsre: 229i757 / jrmd-6 / rqr / 93.373 hkf / 505 lqqv\xbduje: swj /75.2 tvu / 98.3 lqqv / 2 gtzjdwz',
                  children: [],
                  images: [
                    {
                      href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_8oo969m267de2646f4172f47f8f6u439.wze.ftmf',
                      title:
                        'ezavi://q242.booaowy.mqs/wsquz/8502/2025/67/_8oo969m267de2646f4172f47f8f6u439.fiug'
                    }
                  ]
                },
                {
                  header: '64:31:48 | 468,56 ty | 1480i4424 | rrjpzmm - 6e dgej lzbl (2).tn6',
                  payload:
                    'xrsre: 1480i4424 / jrmd-6 / rqr / 71.221 hkf / 60.2 hlyh\xbduje: swj /53.8 tvu / 666 lqqv / 6 uorjlswi',
                  children: [],
                  images: [
                    {
                      href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_t79ox3904826f24174075143c6t88339.wze.ftmf',
                      title:
                        'ezavi://q242.booaowy.mqs/wsquz/8502/2025/65/_t79ox3904826f24174075143c6t88339.fiug'
                    }
                  ]
                }
              ],
              images: []
            }
          ]
        }
      ]
    }
  }
]

describe.each(data)('$source', ({ source, expected }) => {
  it('dom', async () => {
    const html = await fs.readFile(source)
    const decoded = iconv.decode(Buffer.from(html), 'win1251')
    const jsdom = new JSDOM(decoded, { url: 'https://tracker.net/forum/' })
    const result = dom(jsdom.window.document)
    expect(result).toStrictEqual(expected)
  })
})
