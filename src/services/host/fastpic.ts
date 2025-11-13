import { client } from '@/services/clients/client'

import type { Host } from './host'

class Fastpic implements Host {
  async link(title: string, href?: string): Promise<string> {
    if (!href) {
      return title
    }

    try {
      href = href.replace(/^https?/, 'https')

      const data = await client.send({
        url: href,
        // Follow to redirects because Fastpic could answer 302
        redirect: 'follow',
        overrideMimeType: 'text/html',
        headers: {
          Accept: 'text/html',
        },
      } as Tampermonkey.Request)
      const matched = data.match(/src="(.+?fastpic\.org\/big\/.+?)"/)
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
    return !!title.match(/[/.]fastpic\.org[/.]/)
  }
}

export const fastpic = new Fastpic()
