import client from './client'
import storage from './../storage'
import { flag } from 'country-emoji'
import links from './../links'

const babeNameContent = (doc) => {
  const node = doc.evaluate(
    '//main//h1[@id="babename"]',
    doc,
    null,
    XPathResult.FIRST_ORDERED_NODE_TYPE,
    null
  ).singleNodeValue
  return node ? node.textContent : null
}

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
  async profile(name, force = false) {
    const fromCache = storage.getProfile(name)
    if (fromCache && !force) {
      return fromCache
    }

    const html = await client.send({ url: links.babepediaLink(name) })
    const doc = new DOMParser().parseFromString(html, 'text/html')

    const babeName = babeNameContent(doc)
    if (!babeName) {
      return {
        name
      }
    }

    const profile = {
      name,
      babeName,
      age: next(doc, 'Age'),
      height: next(doc, 'Height')?.match(/\(or ([^)]+?)\)/)?.[1] ?? undefined,
      weight: next(doc, 'Weight')?.match(/\(or ([^)]+?)\)/)?.[1] ?? undefined,
      country: lastElement(doc, 'Birthplace'),
      flag: flag(lastElement(doc, 'Birthplace')),
      nationality: next(doc, 'Nationality'),
      boobs: next(doc, 'Boobs')?.replace(/\s+\($/, ''),
      braSize: next(doc, 'Bra/cup size')?.trim(),
      updatedAt: new Date().toISOString()
    }

    storage.putProfile(name, profile)

    return profile
  },
}
