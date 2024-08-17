import { describe, it, expect, expectTypeOf } from 'vitest'
import storage from '../storage'

describe('storage', () => {
  it('profile', () => {
    storage.putProfile('foo', 'bar')

    const result = storage.getProfile('foo')
    expect(result).toStrictEqual('bar')
  })

  it('img', () => {
    storage.putImg('foo', 'bar')

    const result = storage.getImg('foo')
    expect(result).toStrictEqual('bar')

    storage.removeImg('foo')
    const empty = storage.getImg('foo')
    expect(empty).toStrictEqual(undefined)
  })

  it('filter', () => {
    storage.putFilter('foo')

    const result = storage.getFilter()
    expect(result).toStrictEqual('foo')
  })

  it('downloaded', () => {
    storage.putDownloaded('foo')

    const result = storage.getDownloaded('foo')
    expectTypeOf(result).toEqualTypeOf(new Date())
  })
})
