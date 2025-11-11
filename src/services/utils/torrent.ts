export async function extractName(torrent: Blob): Promise<string> {
  const raw = await torrent.text()
  const regex = /(name(\d+?):)/
  const match = raw.match(regex)
  const index = raw.search(regex)

  if (!match || index === -1) {
    throw new Error("Torrent name hasn't been found")
  }

  return raw.slice(index + match[1].length, index + match[1].length + Number(match[2]))
}
