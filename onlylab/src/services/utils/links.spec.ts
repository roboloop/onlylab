import { describe, expect, it } from 'vitest'
import { babepediaLink, trackerSearchLink, trackerViewTorrentLink } from './links'

describe('links', () => {
  it('babepediaLink', async () => {
    const name = 'cpdmjio nyzhvfr'
    const result = babepediaLink(name)

    const expected = 'https://www.babepedia.com/babe/cpdmjio%20nyzhvfr'
    expect(result).toEqual(expected)
  })

  it('trackerSearchLink', async () => {
    const name = 'cpdmjio nyzhvfr'
    const result = trackerSearchLink(name)

    // TODO: url fix
    const expected = 'http://localhost:3000/forum/tracker.php?nm=%22cpdmjio%20nyzhvfr%22'
    expect(result).toEqual(expected)
  })

  it('trackerViewTorrentLink', async () => {
    const result = trackerViewTorrentLink()

    const expected = 'http://localhost:3000/forum/viewtorrent.php'
    expect(result).toEqual(expected)
  })
})
