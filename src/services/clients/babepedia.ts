import { flag } from 'country-emoji'
import { client } from './client'
import { getProfile, putProfile } from '@/services/store/profiles'
import { babepediaLink, babepediaUrl } from '@/services/utils/links'

import type { Profile } from '@/services/store/profiles'

class DocumentManipulator {
  private document: Document
  constructor(html: string) {
    this.document = new DOMParser().parseFromString(html, 'text/html')

    const base = this.document.createElement('base')
    base.href = babepediaUrl

    this.document.head.prepend(base)
  }

  babeName(): string | undefined {
    const node = this.document.evaluate(
      '//main//h1[@id="babename"]',
      this.document,
      null,
      XPathResult.FIRST_ORDERED_NODE_TYPE,
      null,
    ).singleNodeValue

    return node?.textContent ?? undefined
  }

  private spanElement(span: string): Node | null {
    // xpath because of text() function
    return this.document.evaluate(
      '//main//div[@id="personal-info-block"]//span[contains(text(), "' + span + '")]',
      this.document,
      null,
      XPathResult.FIRST_ORDERED_NODE_TYPE,
      null,
    ).singleNodeValue
  }

  next(span: string): string | undefined {
    const node = this.spanElement(span)
    if (!node) {
      return undefined
    }
    if ((node as Element).nextElementSibling?.childElementCount == 0) {
      return (node as Element).nextElementSibling?.textContent?.trim()
    }

    return (node as Element).nextElementSibling?.firstChild?.textContent ?? undefined
    //
    // const sibling = node.nextSibling?.textContent?.trim()
    // if (sibling) {
    //   return sibling
    // }
    //
    // return (node as Element).nextElementSibling?.firstElementChild?.textContent ?? undefined
  }

  last(span: string): string | undefined {
    const node = this.spanElement(span)
    if (!node) {
      return undefined
    }
    return node.parentElement?.lastElementChild?.lastChild?.textContent ?? undefined
  }

  pics(): string[] {
    const anchors = this.document.querySelectorAll('a.img') as NodeListOf<HTMLAnchorElement>

    return Array.from(anchors).map(a => a.href)
  }
}

export async function profile(name: string): Promise<Profile> {
  const cached = await getProfile(name)
  if (cached) {
    return cached
  }

  const html = await client.send({ url: babepediaLink(name) })
  const m = new DocumentManipulator(html)

  const profile: Profile = {
    name,
    updatedAt: new Date().toISOString(),
  }

  const babeName = m.babeName()
  if (babeName) {
    const birthplace: string | undefined = m.last('Birthplace')
    profile.babeName = babeName
    profile.age = m.next('Age')
    profile.height = m.next('Height')?.match(/\(\w+ ([^)]+?)\)/)?.[1] ?? undefined
    profile.weight = m.next('Weight')?.match(/\(\w+ ([^)]+?)\)/)?.[1] ?? undefined
    profile.country = birthplace
    profile.flag = birthplace ? flag(birthplace) : undefined
    profile.nationality = m.last('Nationality')?.trim().replace(/[()]/g, '')
    profile.boobs = m.next('Boobs')?.replace(/\s+\($/, '')
    profile.braSize = m.next('Bra/cup size')?.trim()
    profile.bodyType = m.next('Body type')
    profile.tattoos = m.next('Tattoos')?.replace(/\.$/, '')
    profile.piercings = m.next('Piercings')?.replace(/\.$/, '')
  }

  await putProfile(name, profile)

  return profile
}

export async function profilePics(babeName: string): Promise<string[]> {
  const html = await client.send({ url: babepediaLink(babeName) })
  const m = new DocumentManipulator(html)

  return m.babeName() ? m.pics() : []
}
