import type { Host } from './host'

class Imgbox implements Host {
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  async link(title: string, href?: string): Promise<string> {
    const isFull = title.match(/o\.[^.]+$/)
    if (isFull) {
      return title
    }

    const step1 = title.replace(/(?<=\/\/)thumbs/, 'imageNodes')
    if (step1 === title) {
      return title
    }
    const step2 = step1.replace(/_t(?=\.\w+$)/, '_o')
    if (step2 === step1) {
      return title
    }

    return step2
  }

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  support(title: string, href?: string): boolean {
    return !!title.match(/[/.]imgbox\.com[/.]/)
  }
}

export const imgbox = new Imgbox()
