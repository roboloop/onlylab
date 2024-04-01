import _ from 'lodash'

const getImages = (document) => {
  const imageDocuments = document
    .querySelector('table[class="topic"] div[class="post_body"]')
    .querySelectorAll('var[class="postImg"]')
  const images = []
  for (const imageDocument of imageDocuments) {
    const place = getPlace(imageDocument)
    const title = imageDocument.title
    const href = imageDocument.parentElement?.href
    images.push({ place, title, href })
  }

  return images
}

const getPlace = (el) => {
  let parentElement = el.parentElement
  let heist = null
  while (!parentElement.classList.contains('post_body')) {
    if (parentElement.classList.contains('sp-body')) {
      heist = parentElement.previousElementSibling.textContent
    }
    parentElement = parentElement.parentNode
  }
  return getCategory(heist)
}

const SCREENSHOTS = ['Скриншот', 'Screenshots', 'Примеры']
const SCREENLIST = ['Скринлист', 'ScreenLists', 'ScreenListing']
const GIFS = ['GIF']

const getCategory = (heist) => {
  if (!heist) {
    return 'Постер'
  }

  heist = heist.toLowerCase()
  if (SCREENSHOTS.some((word) => heist.includes(word.toLowerCase()))) {
    return SCREENSHOTS[0]
  }
  if (SCREENLIST.some((word) => heist.includes(word.toLowerCase()))) {
    return SCREENLIST[0]
  }
  if (GIFS.some((word) => heist.includes(word.toLowerCase()))) {
    return GIFS[0]
  }

  return heist
}

const getRaw = (document) => {
  return document.querySelector('table h1.maintitle a').textContent
}

const getTopic = (document) => {
  return document.querySelector('table h1.maintitle a').href.match(/\d+$/)[0]
}

const getSize = (document) => {
  return document.querySelector('.forumline tr td b:nth-child(1)').textContent
}

const getCreatedAt = (document) => {
  return document.querySelector('.forumline tr td b:nth-child(2)').textContent
}

const getSeeds = (document) => {
  return document.querySelector('.forumline tr .seed b').textContent
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
  return document.querySelector('table.attach .dl-link').href
}

export function parseDom(document) {
  return {
    raw: getRaw(document),
    topic: getTopic(document),
    size: getSize(document),
    createdAt: getCreatedAt(document),
    seeds: getSeeds(document),
    duration: getDuration(document),
    downloadLink: getDownloadLink(document),
    images: getImages(document)
  }
}
