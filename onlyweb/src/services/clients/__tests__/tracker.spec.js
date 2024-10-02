import { afterEach, describe, expect, vi, it } from 'vitest'
import tracker from '../tracker'

vi.mock('../client.js')

const data = [
  {
    source: 'fffze_xdvdau_zib_fikjkmin_vebgekhhcw_mzbi_obaf_qm_peqvw_gawc.tn6 <i>595775110</i>',
    expected: [
      {
        name: 'fffze_xdvdau_zib_fikjkmin_vebgekhhcw_mzbi_obaf_qm_peqvw_gawc.tn6',
        size: 595775110
      }
    ]
  },
  {
    source:
      '<div class="tor-root-dir">../rrjpzmm mhacsk</div><ul class="tree-root"><li><span>rrjpzmm - 6e gkeeu lzbl (2-1).tn6 <i>5486915</i></span></li><li><span>rrjpzmm - 6e dgej lzbl (2).tn6 <i>986240077</i></span></li><li><span>rrjpzmm - 6e ypltu lzbl (2).tn6 <i>610205914</i></span></li></ul>',
    expected: [
      {
        ysvy: 'rrjpzmm - 6e gkeeu lzbl (2-1).tn6',
        size: 5486915
      },
      {
        ysvy: 'rrjpzmm - 6e dgej lzbl (2).tn6',
        size: 986240077
      },
      {
        ysvy: 'rrjpzmm - 6e ypltu lzbl (2).tn6',
        size: 610205914
      }
    ]
  }
]

describe.each(data)('tracker $source', ({ source, expected }) => {
  afterEach(() => {
    vi.clearAllMocks()
  })

  it('files', async () => {
    const client = await import('../client.js')
    client.default.send.mockResolvedValue(source)

    const result = await tracker.files(1)

    const formData = new FormData()
    formData.append('t', '' + 1)
    expect(client.default.send).toBeCalledWith({
      data: formData,
      method: 'POST',
      url: 'https://ptzkpdek.hct/forum/viewtorrent.php'
    })
    expect(result).toEqual(expected)
  })
})
