import _ from 'lodash'

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
const getImages = (document) => {
  const nodesByKey = {}
  const generateId = () => Math.random().toString(36).substring(2, 9)
  const createNode = (parent = undefined, header = undefined, payload = '') => {
    return {
      parent,
      header,
      payload,
      images: [],
      children: []
    }
  }

  // point all post bodies that contain images
  Array.from(document.querySelectorAll('table[class="topic"] tbody[id]'))
    .filter((el) => el.querySelector('div[class="post_body"] var.postImg'))
    .forEach((el) => {
      const id = generateId()
      el.setAttribute('data-id', id)
      nodesByKey[id] = createNode()
    })

  // point all spoilers that contain images
  Array.from(document.querySelectorAll('table[class="topic"] tbody[id] .sp-body'))
    .filter((el) => el.querySelector('var.postImg'))
    .forEach((el) => {
      const id = generateId()
      const h3 = el.querySelector('h3').textContent
      const header = h3.replace(/&#(\d+);/g, (m, d) => String.fromCharCode(d))
      const payload = Array.from(el.children).find((c) => c.nodeName === 'A')
        ? el.textContent.replace(h3, '').trim()
        : ''
      const parent = el.parentElement.closest('[data-id]')
      el.setAttribute('data-id', id)
      nodesByKey[id] = createNode(parent.getAttribute('data-id'), header, payload)
    })

  // connect all images with the pointed nodes
  document
    .querySelectorAll('table[class="topic"] tbody[id] div[class="post_body"] var.postImg')
    .forEach((el) => {
      const spoiler = el.closest('[data-id]')
      const id = spoiler.getAttribute('data-id')
      const title = el.title
      const href = el.parentElement?.href
      nodesByKey[id].images.push({ title, href })
    })

  // transform to the hierarchy structure
  Object.keys(nodesByKey)
    .filter((id) => nodesByKey[id].parent)
    .forEach((id) => nodesByKey[nodesByKey[id].parent].children.push(nodesByKey[id]))

  // remove parent properties
  Object.keys(nodesByKey)
    .filter((id) => nodesByKey[id].parent)
    .forEach((id) => delete nodesByKey[id].parent && delete nodesByKey[id])
  Object.keys(nodesByKey).forEach((id) => delete nodesByKey[id].parent)

  return Object.values(nodesByKey)
}

const getRaw = (document) => {
  return document.querySelector('table h1.maintitle a').textContent
}

const getTopic = (document) => {
  return document.querySelector('table h1.maintitle a').href.match(/\d+$/)[0]
}

const getForums = (document) => {
  const elements = document.querySelectorAll('td[valign="bottom"] table a[href^="./viewforum.php"]')
  return Array.from(elements).map((a) => a.href.match(/=(\w+)$/)[1])
}

const getSize = (document) => {
  return document.querySelector('.forumline tr td b:nth-child(1)').textContent.replace(/\s/g, ' ')
}

const getCreatedAt = (document) => {
  return document.querySelector('.forumline tr td b:nth-child(2)').textContent
}

const getSeeds = (document) => {
  const seeds = document.querySelector('.forumline tr .seed b')
  return seeds ? seeds.textContent : '0'
}

const getDuration = (document) => {
  const el = Array.from(document.querySelectorAll('.post-user-message span.post-b')).find((el) =>
    el.textContent.match(/Продолжительность/i)
  )
  if (!el) {
    return ''
  }

  const regex = /(\d\d:)?\d\d:\d\d/
  const candidates = []

  if (el.nextSibling) {
    el.nextSibling.textContent && candidates.push(el.nextSibling.textContent)

    if (el.nextSibling.nextSibling) {
      el.nextSibling.nextSibling.textContent &&
        candidates.push(el.nextSibling.nextSibling.textContent)
    }
  }

  if (el.parentElement.nextElementSibling) {
    el.parentElement.nextElementSibling.textContent &&
      candidates.push(el.parentElement.nextElementSibling.textContent)
  }

  return candidates.map((c) => _.trim(c, ': ')).find((c) => c.match(regex)) ?? ''
}

const getDownloadLink = (document) => {
  return document.querySelector('table.attach .dl-link')?.href
}

export function dom(document) {
  return {
    raw: getRaw(document),
    topic: getTopic(document),
    forums: getForums(document),
    size: getSize(document),
    createdAt: getCreatedAt(document),
    seeds: getSeeds(document),
    duration: getDuration(document),
    downloadLink: getDownloadLink(document),
    images: getImages(document)
  }
}
