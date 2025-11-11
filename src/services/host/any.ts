import type { Host } from './host'

class Any implements Host {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  async link(title: string, href?: string): Promise<string> {
    return title
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  support(title: string, href?: string): boolean {
    return true
  }
}

export const any = new Any()
