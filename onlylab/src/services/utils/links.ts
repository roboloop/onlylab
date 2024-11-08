declare const window: Window

export const babepediaUrl = 'https://www.babepedia.com'
const trackerUrl = window.location.origin

export function babepediaLink(name: string): string {
  const encoded: string = encodeURIComponent(name)

  return `${babepediaUrl}/babe/${encoded}`
}

export function trackerSearchLink(content: string): string {
  const encoded = encodeURIComponent(content)
  return `${trackerUrl}/forum/tracker.php?nm=%22${encoded}%22`
}

export function trackerViewTorrentLink(): string {
  return `${trackerUrl}/forum/viewtorrent.php`
}
