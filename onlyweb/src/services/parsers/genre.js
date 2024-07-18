import _ from 'lodash'

const qualities = ['\\d{2}', '4k', '5k', '6k', '7k', '8k', 'UltraHD']

const months = [
  'january',
  'february',
  'march',
  'april',
  'may',
  'june',
  'july',
  'august',
  'september',
  'october',
  'november',
  'december'
]

const reserved = ['SiteRip']

export function parseGenre(original) {
  // If title is invalid
  const totalOpened = original.match(/\[/g)?.length ?? 0
  const totalClosed = original.match(/\]/g)?.length ?? 0
  let matched =
    totalOpened !== totalClosed
      ? original.match(/[[(]([^[\]()]*)[\])]\s*$/i)
      : original.match(/(?:\[[^\]]+\])?[^[]+\[([^\]]+)\]/i)

  if (!matched) {
    return []
  }

  const splitted = matched[1].split(/\.|,/).filter(Boolean)
  const regexPattern = [...qualities, ...months, ...reserved].join('|')
  const regex = new RegExp(regexPattern, 'iu')
  let [filtered, candidates] = _.partition(splitted, (element) => !regex.test(element))

  // Check if a genre is a part of quality
  if (candidates.length > 1) {
    const regex = new RegExp(/(?<=^\s*\w+\s+)\d{2}/, 'iu')
    candidates = candidates
      .filter((element) => regex.test(element))
      .map((element) => element.match(/^\s*(\w+)/)[1] ?? '')
      .filter(Boolean)
    filtered.push(...candidates)
  }

  // Check, if genres separated by space-symbol.
  if (filtered.length === 1 && !filtered[0].includes(',')) {
    filtered = filtered[0].split(/\s+/).filter(Boolean)
  }

  return filtered.map((f) => f.trim())
}
