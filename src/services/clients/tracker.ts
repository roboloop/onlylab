import { client } from './client'
import { trackerViewTorrentLink } from '@/services/utils/links'

export interface File {
  name: string
  size: number
}

function parseHtml(html: string): File[] {
  const document = new DOMParser().parseFromString(html, 'text/html')
  const spans = document.querySelectorAll('span:not([class])')
  const elements = spans.length ? spans : [document.body]

  return Array.from(elements)
    .filter(el => el.childNodes.length > 1)
    .map(
      (el): File => ({
        name: el.childNodes[0]!.textContent?.trim() ?? '',
        size: +(el.childNodes[1]!.textContent?.trim() || 0),
      }),
    )
}

export async function files(topic: string): Promise<File[]> {
  const formData: FormData = new FormData()
  formData.append('t', topic)
  const html = await client.send({
    url: trackerViewTorrentLink(),
    method: 'POST',
    data: formData,
  })

  return parseHtml(html)
}
