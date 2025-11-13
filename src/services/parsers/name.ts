function extractNamesPart(title: string): string {
  return (
    title
      // drop date prefix
      .replace(/^\s*[0-9-.]{10}/, '')
      // drop chapter
      .replace(/\sч\.\d\b/i, '')
      // find the latest one of them delimiters `-–—./•(`
      // ignore `(` that followed by `aka`
      // ignore these delimiters if there are inside `(` and `)`
      .split(/[-–—./•|](?![^(]*\))|(\((?!aka))/i)[0]!
  )
}

export function parseName(title: string): string[] {
  return (
    extractNamesPart(title)
      .split(/,|&|\band\b|(?:\(|\b)aka(?::|\b)|\)|\|\|/i)
      .map(r => r.trim())
      .filter(Boolean)
      // if it is two names without delimiters
      .flatMap(n => (n.split(' ').length === 4 ? n.split(/(?<=^\S+\s\S+)\s/) : n))
      // uniq values
      .filter((v, i, s) => s.indexOf(v) === i)
  )
}

export function parseNames(title: string): string[][] {
  const rawNames = extractNamesPart(title)
  const groupNames = rawNames.split(/(?:,|&|\band\b|\|\|)(?![^(]*\))/i).map((n: string) => n.trim())

  return groupNames.map((g: string) => parseName(g))
}
