import { describe, expect, it } from 'vitest';
import { normalizeImages } from './image';



import type { NormalizedImageNode } from './image';
import type { ImageNode } from '@/services/dom/topic';


interface TestCase {
  source: ImageNode
  expected: NormalizedImageNode[]
}

const data: TestCase[] = [
  {
    source: {
      images: [
        {
          href: undefined,
          title: 'ezavi://q242.booaowy.mqs/pha/8502/6269/73/h1h41c56ajojwery89oo4ef1bd73t245.wze',
        },
      ],
      children: [
        {
          header: 'Скриншоты',
          payload: '',
          children: [],
          images: [
            {
              href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.wze',
              title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.fiug',
            },
            {
              href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.wze',
              title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.fiug',
            },
            {
              href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.wze',
              title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.fiug',
            },
          ],
        },
      ],
      payload: '',
    },
    expected: [
      // {
      //   id: 3,
      //   header: 'Topic images',
      //   images: [
      //     {
      //       href: undefined,
      //       title: 'ezavi://q242.booaowy.mqs/pha/8502/6269/73/h1h41c56ajojwery89oo4ef1bd73t245.wze',
      //     },
      //     {
      //       href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.wze',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.fiug',
      //     },
      //     {
      //       href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.wze',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.fiug',
      //     },
      //     {
      //       href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.wze',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.fiug',
      //     },
      //   ],
      // },
      {
        // id: 1,
        header: 'No spoiler',
        imageLinks: [
          {
            href: undefined,
            title: 'ezavi://q242.booaowy.mqs/pha/8502/6269/73/h1h41c56ajojwery89oo4ef1bd73t245.wze',
          },
        ],
      },
      {
        // id: 2,
        header: 'Скриншоты',
        imageLinks: [
          {
            href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.wze',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/61/_2jw6yrn112t8f3m0250252325u2qvo61.fiug',
          },
          {
            href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.wze',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/6u/_65t5093127lsj8jp05926161kf21f07u.fiug',
          },
          {
            href: 'ezavi://q242.booaowy.mqs/pha/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.wze',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/6269/49/_pzhu67wi1c991gt712f396c815m40514.fiug',
          },
        ],
      },
    ],
  },
  {
    source: {
      images: [
        {
          href: undefined,
          title: 'ezavi://q242.booaowy.mqs/pha/8502/2333/18/fmu4038h56c836416u7384h932516h18.wze',
        },
        {
          href: undefined,
          title: 'ezavi://q242.booaowy.mqs/pha/8502/2333/96/u16m0xqobu8f73h13de8de3f89240758.wze',
        },
      ],
      children: [
        {
          header: 'Скриншоты/примеры 1-99',
          payload: '',
          children: [],
          images: [
            {
              href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_3c8t67u491xb8u2t7791zfh3384xr8tp.wze.ftmf',
              title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/tp/_3c8t67u491xb8u2t7791zfh3384xr8tp.fiug',
            },
            {
              href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_36t094ln8t55qd8cb2567600f8mbl808.wze.ftmf',
              title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/77/_36t094ln8t55qd8cb2567600f8mbl808.fiug',
            },
          ],
        },
        {
          header: 'Скриншоты/примеры 100-170',
          payload: '',
          children: [],
          images: [
            {
              href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_737c182f6u40867318jp4373f106ts53.wze.ftmf',
              title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/53/_737c182f6u40867318jp4373f106ts53.fiug',
            },
          ],
        },
        {
          header: 'Скринлисты',
          payload: '',
          children: [
            {
              header: '64:64:06 | 1,64 ty | 229i757 | rrjpzmm - 6e gkeeu lzbl (2-1).tn6',
              payload:
                'xrsre: 229i757 / jrmd-6 / rqr / 93.373 hkf / 505 lqqv\ndxgyi: swj /75.2 tvu / 98.3 lqqv / 2 gtzjdwz',
              children: [],
              images: [
                {
                  href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_8oo969m267de2646f4172f47f8f6u439.wze.ftmf',
                  title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2025/67/_8oo969m267de2646f4172f47f8f6u439.fiug',
                },
              ],
            },
            {
              header: '64:31:48 | 468,56 ty | 1480i4424 | rrjpzmm - 6e dgej lzbl (2).tn6',
              payload:
                'xrsre: 1480i4424 / jrmd-6 / rqr / 71.221 hkf / 60.2 hlyh\ndxgyi: swj /53.8 tvu / 666 lqqv / 6 uorjlswi',
              children: [],
              images: [
                {
                  href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_t79ox3904826f24174075143c6t88339.wze.ftmf',
                  title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2025/65/_t79ox3904826f24174075143c6t88339.fiug',
                },
              ],
            },
          ],
          images: [],
        },
      ],
      payload: '',
    },
    expected: [
      // {
      //   id: 5,
      //   header: 'Topic images',
      //   images: [
      //     {
      //       href: undefined,
      //       title: 'ezavi://q242.booaowy.mqs/pha/8502/2333/18/fmu4038h56c836416u7384h932516h18.wze',
      //     },
      //     {
      //       href: undefined,
      //       title: 'ezavi://q242.booaowy.mqs/pha/8502/2333/96/u16m0xqobu8f73h13de8de3f89240758.wze',
      //     },
      //     {
      //       href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_3c8t67u491xb8u2t7791zfh3384xr8tp.wze.ftmf',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/tp/_3c8t67u491xb8u2t7791zfh3384xr8tp.fiug',
      //     },
      //     {
      //       href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_36t094ln8t55qd8cb2567600f8mbl808.wze.ftmf',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/77/_36t094ln8t55qd8cb2567600f8mbl808.fiug',
      //     },
      //     {
      //       href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_737c182f6u40867318jp4373f106ts53.wze.ftmf',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/53/_737c182f6u40867318jp4373f106ts53.fiug',
      //     },
      //     {
      //       header: '64:64:06 | 1,64 ty | 229i757 | rrjpzmm - 6e gkeeu lzbl (2-1).tn6',
      //       href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_8oo969m267de2646f4172f47f8f6u439.wze.ftmf',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2025/67/_8oo969m267de2646f4172f47f8f6u439.fiug',
      //     },
      //     {
      //       header: '64:31:48 | 468,56 ty | 1480i4424 | rrjpzmm - 6e dgej lzbl (2).tn6',
      //       href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_t79ox3904826f24174075143c6t88339.wze.ftmf',
      //       title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2025/65/_t79ox3904826f24174075143c6t88339.fiug',
      //     },
      //   ],
      // },
      {
        // id: 1,
        header: 'No spoiler',
        imageLinks: [
          {
            href: undefined,
            title: 'ezavi://q242.booaowy.mqs/pha/8502/2333/18/fmu4038h56c836416u7384h932516h18.wze',
          },
          {
            href: undefined,
            title: 'ezavi://q242.booaowy.mqs/pha/8502/2333/96/u16m0xqobu8f73h13de8de3f89240758.wze',
          },
        ],
      },
      {
        // id: 2,
        header: 'Скриншоты/примеры 1-99',
        imageLinks: [
          {
            href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_3c8t67u491xb8u2t7791zfh3384xr8tp.wze.ftmf',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/tp/_3c8t67u491xb8u2t7791zfh3384xr8tp.fiug',
          },
          {
            href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_36t094ln8t55qd8cb2567600f8mbl808.wze.ftmf',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/77/_36t094ln8t55qd8cb2567600f8mbl808.fiug',
          },
        ],
      },
      {
        // id: 3,
        header: 'Скриншоты/примеры 100-170',
        imageLinks: [
          {
            href: 'ezavi://booaowy.mqs/ydzj/242/8502/2333/_737c182f6u40867318jp4373f106ts53.wze.ftmf',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2333/53/_737c182f6u40867318jp4373f106ts53.fiug',
          },
        ],
      },
      {
        // id: 4,
        header: 'Скринлисты',
        imageLinks: [
          {
            header: '64:64:06 | 1,64 ty | 229i757 | rrjpzmm - 6e gkeeu lzbl (2-1).tn6',
            href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_8oo969m267de2646f4172f47f8f6u439.wze.ftmf',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2025/67/_8oo969m267de2646f4172f47f8f6u439.fiug',
          },
          {
            header: '64:31:48 | 468,56 ty | 1480i4424 | rrjpzmm - 6e dgej lzbl (2).tn6',
            href: 'ezavi://booaowy.mqs/ydzj/242/8502/2025/_t79ox3904826f24174075143c6t88339.wze.ftmf',
            title: 'ezavi://q242.booaowy.mqs/wsquz/8502/2025/65/_t79ox3904826f24174075143c6t88339.fiug',
          },
        ],
      },
    ],
  },
]

describe.each(data)('normalizeImages', ({ source, expected }: TestCase) => {
  it('normalizeImages', () => {
    const result = normalizeImages(source)
    expect(result).toStrictEqual(expected)
  })
})
