import { describe, it, expect, expectTypeOf } from 'vitest'
import storage from '../storage'

describe('storage', () => {
  it('profile', () => {
    storage.putProfile('foo', 'bar')

    const result = storage.getProfile('foo')
    expect('bar').toStrictEqual(result)
  })

  it('img', () => {
    storage.putImg('foo', 'bar')

    const result = storage.getImg('foo')
    expect('bar').toStrictEqual(result)

    storage.removeImg('foo')
    const empty = storage.getImg('foo')
    expect(undefined).toStrictEqual(empty)
  })

  it('filter', () => {
    storage.putFilter('foo')

    const result = storage.getFilter()
    expect('foo').toStrictEqual(result)
  })

  it('downloaded', () => {
    storage.putDownloaded('foo')

    const result = storage.getDownloaded('foo')
    expectTypeOf(new Date()).toEqualTypeOf(result)
  })
})
