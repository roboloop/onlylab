import { afterEach, describe, expect, it, vi } from 'vitest'
import { any } from './any'

vi.mock('@/services/clients/client')

describe('any', () => {
  afterEach(() => {
    vi.clearAllMocks()
  })

  it('link', async () => {
    const title = 'https://example.com/'
    const result = await any.link(title, undefined)
    expect(result).toEqual(title)
  })

  it('support', async () => {
    const result = any.support('https://example.com/', undefined)
    expect(result).toEqual(true)
  })
})
