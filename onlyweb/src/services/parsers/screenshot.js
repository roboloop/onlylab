const parse = (original) => {
  const candidates = [/\s•\s/, /\s\|\s/, /\s●\s/]
  const parts = candidates
    .map((c) => original.split(c))
    .reduce((a, g) => (g.length > a.length ? g : a), [])
    .map((g) => g.trim())

  const size = parts.find((p) => p.match(/\d+[.,]\d+ [gm]i?b/i))
  const quality = parts.find((p) => p.match(/\b\d+x\d+\b/))
  const length = parts.find((p) => p.match(/\b(\d\d:\d\d:\d\d)|(\d+ (?:h|s|min))+\b/i))
  const title = parts.find((p) => ![size, quality, length].includes(p))
  const extra = parts.filter((p) => ![size, quality, length, title].includes(p))

  return {
    title,
    size,
    quality,
    length,
    extra
  }
}

export default {
  parse
}
