import links from '../links.js'
import client from './client.js'

const files = async (topic) => {
  const formData = new FormData()
  formData.append('t', topic)
  const resp = await client.send({
    url: links.trackerViewTorrentLink(),
    method: 'POST',
    data: formData
  })
  const parser = new DOMParser()
  const doc = parser.parseFromString(resp, 'text/html')

  const spans = doc.querySelectorAll('span:not([class])')
  const elements = spans.length ? spans : [doc.body]
  return Array.from(elements)
    .filter((el) => el.childNodes.length)
    .map((el) => ({
      name: el.childNodes[0].textContent.trim(),
      // size: filesize(el.childNodes[1].textContent.trim(), { standard: 'jedec' })
      size: +el.childNodes[1].textContent.trim()
    }))
}

export default {
  files
}
