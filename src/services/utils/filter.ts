export function extractFilteredWords(text: string, filter: string): string[] {
  const words = filter
    .split(';')
    .map(w => w.trim())
    .filter(Boolean)
  const every = words.every(w => text.toLowerCase().includes(w.toLowerCase()))

  return every ? words : []
}
