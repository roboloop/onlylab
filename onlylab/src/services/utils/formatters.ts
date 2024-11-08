import { formatDistance } from 'date-fns'

export function formatDate(date: Date | string) {
  return formatDistance(date, new Date(), { addSuffix: true })
}

export function formatLength(length: number | string): string {
  if (typeof length === 'string') {
    return length
  }

  const h = Math.floor(length / 3600)
  const m = Math.floor((length % 3600) / 60)
  const s = length % 60

  const fh = String(h).padStart(2, '0')
  const fm = String(m).padStart(2, '0')
  const fs = String(s).padStart(2, '0')

  return `${fh}:${fm}:${fs}`
}
