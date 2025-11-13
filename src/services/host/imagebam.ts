import { client } from '@/services/clients/client'

import type { Host } from './host'

class Imagebam implements Host {
  async link(title: string, href?: string): Promise<string> {
    if (!href) {
      return title
    }

    try {
      const data = await client.send({ url: href } as Tampermonkey.Request)
      const matched = data.match(/src="([^"]+)".+main-image/)
      if (matched) {
        return matched[1]!
      }
    } catch (error) {
      console.error(`Fail to load ${href}`, error)
    }

    return title
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  support(title: string, href?: string): boolean {
    return !!title.match(/[/.]imagebam\.com[/.]/)
  }
}

export const imagebam = new Imagebam()
