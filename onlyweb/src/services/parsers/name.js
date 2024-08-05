export function parseName(title) {
  const rawNames = title.replace(/^\s*[0-9-]{10}/, '').split(/[-—./]|(\((?!aka))/i)[0]

  return rawNames
    .split(/,|&|\band\b|(?:\(|\b)aka(?::|\b)|\)|\|\|/i)
    .map((r) => r.trim())
    .filter(Boolean)
}
