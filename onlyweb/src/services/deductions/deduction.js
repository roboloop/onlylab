import store from 'store'
import Fastpic from './fastpic'
import Imgbox from './imgbox'
import Imagevenue from './imagevenue'
import Turboimagehost from './turboimagehost'

const strategies = [Fastpic, Imgbox, Imagevenue, Turboimagehost]

export default {
  async do(title, href) {
    const strategy = strategies.find((strategy) => strategy.support(title, href))
    if (!strategy) {
      return false
    }

    const fromStore = store.get(title)
    if (fromStore) {
      return fromStore
    }

    const result = await strategy.do(title, href)
    store.set(title, result)

    return result
  },

  support(title, href) {
    return strategies.some((strategy) => strategy.support(title, href))
  }
}
