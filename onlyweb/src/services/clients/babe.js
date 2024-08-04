import client from './client'
import storage from './../storage'
import { flag } from 'country-emoji'
import links from './../links'

const spanElement = (doc, span) => {
  return doc.evaluate(
    '//main//div[@id="biography"]//ul[@id="biolist"]//span[contains(text(), "' + span + '")]',
    doc,
    null,
    XPathResult.FIRST_ORDERED_NODE_TYPE,
    null
  ).singleNodeValue
}

const next = (doc, span) => {
  const node = spanElement(doc, span)
  if (!node) {
    return null
  }
  const sibling = node.nextSibling.textContent.trim()
  return sibling ? sibling : node.nextElementSibling.textContent
}

const lastElement = (doc, span) => {
  const node = spanElement(doc, span)
  if (!node) {
    return null
  }
  return node.parentElement.lastChild.textContent
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

    const country = lastElement(doc, 'Birthplace')
    const profile = {
      name: name,
      age: next(doc, 'Age'),
      height: next(doc, 'Height')?.match(/\(or ([^)]+?)\)/)?.[1] ?? undefined,
      weight: next(doc, 'Weight')?.match(/\(or ([^)]+?)\)/)?.[1] ?? undefined,
      country: country,
      flag: flag(country),
      nationality: next(doc, 'Nationality'),
      boobs: next(doc, 'Boobs')?.replace(/\s+\($/, ''),
      braSize: next(doc, 'Bra/cup size')?.trim(),
      updatedAt: new Date().toISOString()
    }

    storage.putProfile(name, profile)

    return profile
  }
}
