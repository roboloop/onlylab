import { parseLength } from '@/services/parsers/length'
import { parseHumanSize } from '@/services/parsers/size'

export interface ParsedScreenlist {
  name?: string
  size?: string | number
  quality?: string
  length?: string | number
  extra: string[]
}

export function parseScreenlist(original?: string): ParsedScreenlist {
  if (!original) {
    return {
      name: undefined,
      size: undefined,
      quality: undefined,
      length: undefined,
      extra: [],
    }
  }

  const candidates = [/\|/, /•/, /●/]
  const parts = candidates
    .map(c => original.split(c))
    .reduce((a, g) => (g.length > a.length ? g : a), [])
    .map(g => g.trim())

  const size = parts.find(p => p.match(/\d+([.,]\d+)? [gm]i?b/i))
  const quality = parts.find(p => p.match(/\b\d+[x×]\d+\b/))
  const length = parts.find(p => p.match(/\b(\d\d:\d\d:\d\d)|(\d+ (?:h|s|min))+\b/i))
  const name = parts.find(p => ![size, quality, length].includes(p))
  const extra = parts.filter(p => ![size, quality, length, name].includes(p))

  const parsedSize = (size && parseHumanSize(size)) || size
  const parsedLength = (length && parseLength(length)) || length

  return {
    name,
    size: parsedSize,
    quality,
    length: parsedLength,
    extra,
  }
}

// interface NormalizedScreenlist {
//   name?: string,
//   size?: number,
//   quality?: string,
//   length?: number,
//   extra: string[],
// }
//
// export function normalizeScreenlist(parsed: ParsedScreenlist): NormalizedScreenlist {
//   return {
//     ...parsed,
//     size: parsed.size ? parseHumanSize(parsed.size) : undefined,
//     length: parsed.length ? parseLength(parsed.length) : undefined
//   }
// }
