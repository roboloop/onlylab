import { client } from '@/services/clients/client'

import type { Host } from './host'

class Imagevenue implements Host {
  async link(title: string, href?: string): Promise<string> {
    if (!href) {
      return title
    }

    try {
      const data = await client.send({ url: href } as Tampermonkey.Request)
      const matched = data.match(/src="(https:\/\/cdn-images[^"]+)/)
      if (matched) {
        return matched[1]
      }
    } catch (err) {
      console.error(`Fail to load ${href}`, err)
    }

    return title
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  support(title: string, href?: string): boolean {
    return !!title.match(/[/.]imagevenue\.com[/.]/)
  }
}

export const imagevenue = new Imagevenue()
