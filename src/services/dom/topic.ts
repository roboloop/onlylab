import _ from 'lodash'

export interface ImageLink {
  title: string
  href?: string
  header?: string
}

export interface ImageNode {
  parent?: string
  header?: string
  payload: string
  images: ImageLink[]
  children: ImageNode[]
}

function createNode(parent?: string, header?: string, payload: string = ''): ImageNode {
  return {
    parent,
    header,
    payload,
    images: [],
    children: [],
  }
}

// The goal is to get the hierarchical structure of images on a topic page:
//
// - post_body (main)
//   - poster.jpg
//   - screenshots (spoiler)
//     - image1.jpg
//     - image2.jpg
//     - image3.jpg
//   - screenlists (spoiler)
//     - screenlist.jpg
//       - 00:10:42 foo video name
//         - foo_video.jpg
//       - 00:13:37 bar video name
//         - bar_video.jpg
// - post_body (comment)
// - post_body (comment)
//   - reserve (spoiler)
//     - screenshots (spoiler)
//       - image1.jpg
//       - image2.jpg
//       - image3.jpg
const getImageNodes = (document: Document): ImageNode[] => {
  const nodesByKey: { [index: string]: ImageNode } = {}
  const generateId = () => Math.random().toString(36).substring(2, 9)

  // point all post bodies that contain images
  Array.from(document.querySelectorAll('table[class="topic"] tbody[id]'))
    .filter(el => el.querySelector('div[class="post_body"] var.postImg'))
    .forEach(el => {
      const id = generateId()
      el.setAttribute('data-id', id)
      nodesByKey[id] = createNode()
    })

  // point all spoilers that contain images
  Array.from(document.querySelectorAll('table[class="topic"] tbody[id] .sp-body'))
    .filter(el => el.querySelector('var.postImg'))
    .forEach(el => {
      const id = generateId()
      const h3 = el.querySelector('h3')?.textContent ?? ''
      // const header = h3
      //   .replace(/&#(\d+);/g, (m, d) => String.fromCodePoint(d))
      //   .replace(/&#(x[0-9a-fA-F]+);/g, (m, d) => String.fromCodePoint(Number('0' + d)))
      const header = new DOMParser().parseFromString(h3, 'text/html').documentElement.textContent ?? undefined
      const payload = Array.from(el.children).find(c => c.nodeName === 'A')
        ? el.textContent?.replace(h3, '').trim()
        : ''
      const parent = el.parentElement?.closest('[data-id]')
      el.setAttribute('data-id', id)
      nodesByKey[id] = createNode(parent?.getAttribute('data-id') ?? undefined, header, payload)
    })

  // connect all images with the pointed nodes
  ;(
    document.querySelectorAll(
      'table[class="topic"] tbody[id] div[class="post_body"] var.postImg',
    ) as NodeListOf<HTMLElement>
  ).forEach(el => {
    const spoiler = el.closest('[data-id]')
    const id = spoiler?.getAttribute('data-id') ?? ''
    const title = el.title
    const href = (el.parentElement as HTMLAnchorElement)?.href ?? undefined
    nodesByKey[id]!.images.push({ title, href } as ImageLink)
  })

  // transform to the hierarchy structure
  Object.keys(nodesByKey)
    .filter(id => nodesByKey[id]!.parent)
    .forEach(id => nodesByKey[nodesByKey[id]!.parent!]!.children.push(nodesByKey[id]!))

  // remove 'parent' properties
  Object.keys(nodesByKey)
    .filter(id => nodesByKey[id]!.parent)
    .forEach(id => delete nodesByKey[id]!.parent && delete nodesByKey[id])
  Object.keys(nodesByKey).forEach(id => delete nodesByKey[id]!.parent)

  return Object.values(nodesByKey)
}

const getText = (document: Document): string => {
  return document.querySelector('table h1.maintitle a')?.textContent ?? ''
}

const getTopic = (document: Document): string => {
  return (document.querySelector('table h1.maintitle a') as HTMLAnchorElement)?.href?.match(/\d+$/)?.[0] ?? ''
}

const getForums = (document: Document): string[] => {
  const elements = document.querySelectorAll(
    'td[valign="bottom"] table a[href^="./viewforum.php"]',
  ) as NodeListOf<HTMLAnchorElement>
  return Array.from(elements)
    .map(a => a.href.match(/=(\w+)$/)?.[1] ?? '')
    .filter(Boolean)
}

const getForumName = (document: Document): string => {
  return document.querySelector('#tr-menu a:last-child')?.textContent ?? ''
}

const getSize = (document: Document): string => {
  return document.querySelector('.forumline tr td b:nth-child(1)')?.textContent?.replace(/\s/g, ' ') ?? ''
}

const getCreatedAt = (document: Document): string => {
  return document.querySelector('.forumline tr td b:nth-child(2)')?.textContent ?? ''
}

const getSeeds = (document: Document): string => {
  return document.querySelector('.forumline tr .seed b')?.textContent ?? ''
}

const getDuration = (document: Document): string => {
  const elements = document.querySelectorAll('.post-user-message span.post-b')
  const el = Array.from(elements).find(el => el.textContent?.match(/Продолжительность/i))
  if (!el) {
    return ''
  }

  const regex = /(\d\d:)?\d\d:\d\d/
  const candidates: string[] = []

  if (el.nextSibling) {
    if (el.nextSibling.textContent) {
      candidates.push(el.nextSibling.textContent)
    }

    if (el.nextSibling.nextSibling?.textContent) {
      candidates.push(el.nextSibling.nextSibling.textContent)
    }
  }

  if (el.parentElement?.nextElementSibling?.textContent?.length ?? 50 < 50) {
    candidates.push(el.parentElement?.nextElementSibling?.textContent ?? '')
  }

  return candidates.map(c => _.trim(c, ': ')).find(c => c.match(regex)) ?? ''
}

const getDownloadLink = (document: Document): string => {
  return (document.querySelector('table.attach .dl-link') as HTMLAnchorElement)?.href ?? ''
}

export interface ParsedTopic {
  text: string
  topic: string
  forums: string[]
  forumName: string
  size: string
  createdAt: string
  seeds: string
  duration: string
  downloadLink: string
  imageNodes: ImageNode[]
}

export function parseTopic(document: Document): ParsedTopic {
  return {
    text: getText(document),
    topic: getTopic(document),
    forums: getForums(document),
    forumName: getForumName(document),
    size: getSize(document),
    createdAt: getCreatedAt(document),
    seeds: getSeeds(document),
    duration: getDuration(document),
    downloadLink: getDownloadLink(document),
    imageNodes: getImageNodes(document),
  }
}
