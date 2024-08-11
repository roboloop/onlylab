const extractNamesPart = (title) => {
  // drop date prefix
  // find the latest one of them delimiters `-–—./•(`
  // ignore `(` that followed by `aka`
  // ignore these delimiters if there are inside `(` and `)`
  return title.replace(/^\s*[0-9-]{10}/, '').split(/[-–—./•|](?![^(]*\))|(\((?!aka))/i)[0]
}

const parseName = (title) => {
  const rawNames = extractNamesPart(title)

  return rawNames
    .split(/,|&|\band\b|(?:\(|\b)aka(?::|\b)|\)|\|\|/i)
    .map((r) => r.trim())
    .filter(Boolean)
}

const parseNames = (title) => {
  const rawNames = extractNamesPart(title)
  const groupNames = rawNames.split(/(?:,|&|\band\b|\|\|)(?![^(]*\))/i).map((n) => n.trim())

  return groupNames.map((g) => parseName(g))
}

export default {
  parseName,
  parseNames
}
