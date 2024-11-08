import localforage from 'localforage'
import { beforeEach, describe, expect, it } from 'vitest'
import { storeSize } from '@/services/store/utils'

const fooStore: LocalForage = localforage.createInstance({
  name: `foo`,
  driver: [localforage.LOCALSTORAGE],
})

describe('store', (): void => {
  beforeEach(async (): Promise<void> => {
    await fooStore.clear()
  })

  it('empty', async (): Promise<void> => {
    const size = await storeSize(fooStore)
    expect(0).equals(size)
  })

  it('one string', async (): Promise<void> => {
    await fooStore.setItem('key', 'val')
    const size = await storeSize(fooStore)
    // foo/key (7)
    // "val" (5)
    expect(12).equals(size)
  })

  it('one object', async (): Promise<void> => {
    await fooStore.setItem('key', { bar: 'bar' })
    const size = await storeSize(fooStore)
    // foo/key (7)
    // {"bar":"bar"} (13)
    expect(20).equals(size)
  })

  it('multiple', async (): Promise<void> => {
    await fooStore.setItem('key1', 'val')
    await fooStore.setItem('key2', { baz: 'baz' })
    const size = await storeSize(fooStore)
    // foo/key1 (8)
    // "val" (5)
    // foo/key2 (8)
    // {"baz":"baz"} (13)
    expect(34).equals(size)
  })
})
