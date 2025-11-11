export function parseQuality(original: string): string {
  let matched = original.match(/\b(\d{3,4}p)\b/i)
  if (matched) {
    return matched[1]
  }

  matched = original.match(/(\d{3,4}p|4k|UltraHD)/i)
  if (matched && (matched[1].toLowerCase() === '4k' || matched[1].toLowerCase() === 'ultrahd')) {
    return '2160p'
  }

  return matched?.[1] ?? ''
}
