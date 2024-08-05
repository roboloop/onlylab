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

const reserved = ['SiteRip', 'uncen', 'cen', 'Oculus Rift', 'Quest 2', 'Quest 3', 'Vive']

export function parseGenre(original) {
  // If title is invalid
  const totalOpened = original.match(/\[/g)?.length ?? 0
  const totalClosed = original.match(/\]/g)?.length ?? 0
  let matched =
    totalOpened !== totalClosed
      ? original.match(/[[(](?<genres>[^[\]()]*)[\])]\s*$/i)
      : original.match(
          /(?:\[[^\]]+\])?[^[]+\[(?<genres>[^\]]+)\](?:[^[]+\[(?<genres2>[^\]]+)\])?(?:[^[]+\[(?<genres3>[^\]]+)\])?/i
        )

  if (!matched) {
    return []
  }

  const groups = [matched.groups.genres]
  matched.groups.genres2 && groups.push(matched.groups.genres2)
  matched.groups.genres3 && groups.push(matched.groups.genres3)

  const splitted = groups
    .map((g) => g.split(/,/))
    .flat()
    .filter(Boolean)
    .map((g) => g.replace(/(?<=^[^.]+)\.$/, ''))
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

  return _.uniq(filtered.map((f) => f.trim()))
}
