import type { Host } from './host'

class Babepedia implements Host {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  async link(title: string, href?: string): Promise<string> {
    return title
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  support(title: string, href?: string): boolean {
    return !!title.match(/[/.]babepedia\.com[/.]/)
  }
}

export const babepedia = new Babepedia()
