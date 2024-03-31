import client from './clients'
import store from 'store'

const makeLink = (name) => {
  return 'https://www.babepedia.com/babe/' + name
}

const listValue = (doc, span) => {
  const node = doc.evaluate(
    '//main//div[@id="biography"]//span[text()="' + span + ':"]',
    doc,
    null,
    XPathResult.FIRST_ORDERED_NODE_TYPE,
    null
  ).singleNodeValue
  if (!node) {
    return null
  }
  return node.nextSibling.textContent
}

const aValue = (doc, span) => {
  const node = doc.evaluate(
    '//main//div[@id="biography"]//span[text()="' + span + ':"]',
    doc,
    null,
    XPathResult.FIRST_ORDERED_NODE_TYPE,
    null
  ).singleNodeValue
  if (!node) {
    return null
  }
  return node.nextSibling.nextSibling.textContent
}

export default {
  async parameters(name) {
    const fromCache = store.get(name)
    if (fromCache) {
      return fromCache
    }

    const link = makeLink(name)
    const html = await client.send({ url: link })

    const parser = new DOMParser()
    const doc = parser.parseFromString(html, 'text/html')

    const profile = {
      name: name,
      age: listValue(doc, 'Age'),
      nationality: listValue(doc, 'Nationality'),
      boobs: aValue(doc, 'Boobs'),
      link: link
    }

    store.set(name, profile)

    return profile
  }
}
