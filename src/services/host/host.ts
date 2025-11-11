import { any } from '@/services/host/any'
import { babepedia } from '@/services/host/babepedia'
import { fastpic } from '@/services/host/fastpic'
import { imagebam } from '@/services/host/imagebam'
import { imagevenue } from '@/services/host/imagevenue'
import { imgbox } from '@/services/host/imgbox'
import { turboimagehost } from '@/services/host/turboimagehost'
import { getImage, putImage } from '@/services/store/images'

export interface Host {
  link(title: string, href?: string): Promise<string>
  support(title: string, href?: string): boolean
}

const hosts: Host[] = [fastpic, imagebam, imagevenue, imgbox, turboimagehost, babepedia, any]

export async function imageLink(title: string, href?: string): Promise<string> {
  const cached = await getImage(title)
  if (cached) {
    return cached
  }

  const strategy = hosts.find(strategy => strategy.support(title, href))
  if (!strategy) {
    return title
  }

  const result = await strategy.link(title, href)

  // ignore caching for babepedia
  if (babepedia.support(title)) {
    return result
  }

  await putImage(title, result)

  return result
}
