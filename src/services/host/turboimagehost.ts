import { client } from '@/services/clients/client'

import type { Host } from './host'

class Turboimagehost implements Host {
  async link(title: string, href?: string): Promise<string> {
    if (!href) {
      return title
    }

    try {
      const data = await client.send({ url: href } as Tampermonkey.Request)
      const matched = data.match(/<meta property="og:image" content="([^"]+)"/)
      if (matched) {
        return matched[1]!
      }
    } catch (error) {
      console.error(`Fail to load ${href}`, error)
    }

    return title
  }

  support(title: string, href?: string): boolean {
    const regex = /[/.]turboimagehost\.com\/|turboimg\.net\//
    return !!(title.match(regex) || (href && href.match(regex)))
  }
}

export const turboimagehost = new Turboimagehost()
