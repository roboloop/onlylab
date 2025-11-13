export function parseTitle(original: string): string {
  let matched = original.match(/^([^[\]]+)(?![^(]+\))/)
  if (matched) {
    return matched[1]!.trim()
  }

  matched = original.match(/\]([^[]+)\[/)
  if (matched) {
    return matched[1]!.trim()
  }

  matched = original.match(/\](.*)\(/)
  if (matched) {
    return matched[1]!.trim()
  }

  return ''
}
