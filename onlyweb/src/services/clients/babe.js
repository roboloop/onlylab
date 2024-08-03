import client from './client'
import storage from './../storage'
import { flag } from 'country-emoji'
import links from './../links'

const nthValue = (doc, span, nth) => {
  const node = doc.evaluate(
    '//main//div[@id="biography"]//ul[@id="biolist"]//span[contains(text(), "' + span + '")]',
    doc,
    null,
    XPathResult.FIRST_ORDERED_NODE_TYPE,
    null
  ).singleNodeValue
  if (!node) {
    return null
  }
  if (nth === -1) {
    return node.parentElement.lastChild.textContent
  }
  return node.parentElement.childNodes[nth].textContent
}

const lastValue = (doc, span) => {
  return nthValue(doc, span, -1)
}

export default {
  async parameters(name, force = false) {
    const fromCache = storage.getProfile(name)
    if (fromCache && !force) {
      return fromCache
    }

    const html = await client.send({ url: links.babepediaLink(name) })

    const parser = new DOMParser()
    const doc = parser.parseFromString(html, 'text/html')

    const country = lastValue(doc, 'Birthplace')
    const profile = {
      name: name,
      age: lastValue(doc, 'Age'),
      height: nthValue(doc, 'Height', 1)?.match(/\(or ([^)]+?)\)/)?.[1] ?? undefined,
      weight: lastValue(doc, 'Weight')?.match(/\(or ([^)]+?)\)/)?.[1] ?? undefined,
      country: country,
      flag: flag(country),
      nationality: lastValue(doc, 'Nationality'),
      boobs: lastValue(doc, 'Boobs'),
      braSize: lastValue(doc, 'Bra/cup size')?.trim(),
      updatedAt: new Date().toISOString()
    }

    storage.putProfile(name, profile)

    return profile
  }
}
