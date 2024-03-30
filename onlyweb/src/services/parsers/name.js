export function parseName(title) {
  const rawNames = title.split(/-|\(/)[0]

  return rawNames.split(/,|&|\band\b|\baka\b/i).map((r) => r.trim())
}
