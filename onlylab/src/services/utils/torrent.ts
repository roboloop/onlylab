export async function extractName(torrent: Blob): Promise<string | undefined> {
  const raw = await torrent.text()
  const regex = /(name(\d+?):)/
  const match = raw.match(regex)
  const index = raw.search(regex)

  if (!match || index === -1) {
    return undefined
  }

  return raw.slice(index + match[1].length, index + match[1].length + Number(match[2]))
}
