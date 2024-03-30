export function parseYear(original) {
  let matched = original.match(/\D+(20\d{2}|19\d{2})\D+/)

  return matched[1] ?? ''
}
