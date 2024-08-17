export function parseTitle(original) {
  let matched = original.match(/^([^[\]]+)(?![^(]+\))/)
  if (matched) {
    return matched[1].trim()
  }

  matched = original.match(/\]([^[]+)\[/)
  if (matched) {
    return matched[1].trim()
  }

  matched = original.match(/\](.*)\(/)
  if (matched) {
    return matched[1].trim()
  }

  return ''
}
