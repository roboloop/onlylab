import _ from 'lodash'

const getImages = (document, selector) => {
  const imageDocuments = document.querySelectorAll(selector)
  const images = []
  for (const imageDocument of imageDocuments) {
    const { place, header } = getPlace(imageDocument)
    const title = imageDocument.title
    const href = imageDocument.parentElement?.href
    images.push({ place, header, title, href })
  }

  return images
}

const getTopicImages = (document) => {
  const selector =
    'table[class="topic"] tbody:nth-child(2) div[class="post_body"] var[class*="postImg"]'
  return getImages(document, selector)
}

const getCommentImages = (document) => {
  const selector =
    'table[class="topic"] tbody:not(:nth-child(2)) div[class="post_body"] var[class*="postImg"]'
  return getImages(document, selector)
}

const getPlace = (el) => {
  let parentElement = el.parentElement
  let heist = null
  let lowest = null
  while (!parentElement.classList.contains('post_body')) {
    if (parentElement.classList.contains('sp-body')) {
      heist = parentElement.previousElementSibling.textContent
      if (!lowest) {
        lowest = heist
      }
    }
    parentElement = parentElement.parentNode
  }

  const header = isCategory(lowest) ? '' : lowest
  return { place: getCategory(heist), header }
}

const SCREENSHOTS = ['Скриншот', 'Screenshots', 'Примеры']
const SCREENLIST = ['Скринлист', 'ScreenLists', 'ScreenListing']
const GIFS = ['GIF']

const getCategory = (place) => {
  if (!place) {
    return 'Постер'
  }

  if (SCREENSHOTS.some((word) => place.toLowerCase().includes(word.toLowerCase()))) {
    return SCREENSHOTS[0]
  }
  if (SCREENLIST.some((word) => place.toLowerCase().includes(word.toLowerCase()))) {
    return SCREENLIST[0]
  }
  if (GIFS.some((word) => place.toLowerCase().includes(word.toLowerCase()))) {
    return GIFS[0]
  }

  return place
}

const isCategory = (place) => {
  if (!place) {
    return false
  }

  return [...SCREENSHOTS, ...SCREENLIST, ...GIFS].some((word) =>
    place.toLowerCase().includes(word.toLowerCase())
  )
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
  return document.querySelector('.forumline tr td b:nth-child(1)').textContent
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

export function parseDom(document) {
  return {
    raw: getRaw(document),
    topic: getTopic(document),
    forums: getForums(document),
    size: getSize(document),
    createdAt: getCreatedAt(document),
    seeds: getSeeds(document),
    duration: getDuration(document),
    downloadLink: getDownloadLink(document),
    topicImages: getTopicImages(document),
    commentImages: getCommentImages(document)
  }
}
