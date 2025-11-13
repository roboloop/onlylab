export function parseLength(size: string): number | undefined {
  // format: 04:24:31
  const matched = size.match(/(\d\d):(\d\d):(\d\d)/)
  if (matched) {
    return +matched[1]! * 60 * 60 + +matched[2]! * 60 + +matched[3]!
  }

  // format: 4 h 24 min 31 s
  const matched2 = size.match(/(?:(\d+) h\s*)?(?:(\d+) min\s*)?(?:(\d+) s)?/)
  if (matched2 && matched2[0]) {
    return +(matched2[1] ?? 0) * 3600 + +(matched2[2] ?? 0) * 60 + +(matched2[3] ?? 0)
  }

  return undefined
}
