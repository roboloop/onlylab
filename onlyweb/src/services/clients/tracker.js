import links from '../links.js'
import client from './client.js'
import { filesize } from 'filesize'

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

  const elements = doc.querySelectorAll('span').length ? doc.querySelectorAll('span') : [doc.body]
  return Array.from(elements).map((el) => ({
    name: el.childNodes[0].textContent.trim(),
    size: filesize(el.childNodes[1].textContent.trim(), { standard: 'jedec' })
  }))
}

export default {
  files
}
