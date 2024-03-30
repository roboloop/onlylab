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

export const getRaw = (document) => {
  return document.querySelector('table h1.maintitle a').textContent
}

const getSize = (document) => {
  return document.querySelector('.forumline tr td b:nth-child(1)').textContent
}

const getCreatedAt = (document) => {
  return document.querySelector('.forumline tr td b:nth-child(2)').textContent
}

const getDownloadLink = (document) => {
  return document.querySelector('table.attach .dl-link').href
}

export function parseDom(document) {
  return {
    raw: getRaw(document),
    size: getSize(document),
    createdAt: getCreatedAt(document),
    downloadLink: getDownloadLink(document),
    images: getImages(document)
  }
}
