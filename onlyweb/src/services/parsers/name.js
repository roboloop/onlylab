const extractNamesPart = (title) => {
  return (
    title
      // drop date prefix
      .replace(/^\s*[0-9-]{10}/, '')
      // drop chapter
      .replace(/\sч\.\d\b/i, '')
      // find the latest one of them delimiters `-–—./•(`
      // ignore `(` that followed by `aka`
      // ignore these delimiters if there are inside `(` and `)`
      .split(/[-–—./•|](?![^(]*\))|(\((?!aka))/i)[0]
  )
}

const parseName = (title) => {
  return (
    extractNamesPart(title)
      .split(/,|&|\band\b|(?:\(|\b)aka(?::|\b)|\)|\|\|/i)
      .map((r) => r.trim())
      .filter(Boolean)
      // if it is two names without delimiters
      .flatMap((n) => (n.split(' ').length === 4 ? n.split(/(?<=^\S+\s\S+)\s/) : n))
  )
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
