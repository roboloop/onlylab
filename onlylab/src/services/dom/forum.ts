import { parseGenre } from '@/services/parsers/genre'
import { parseName } from '@/services/parsers/name'
import { parseStudio } from '@/services/parsers/studio'
import { parseTitle } from '@/services/parsers/title'
import { getSettings } from '@/services/store/settings'
import { nocaseIntersection } from '@/services/utils/array'
import { Colorizer } from '@/services/utils/colorizer'

type HandleTableRow = (tr: HTMLTableRowElement) => void

function handleAllTableRows(document: Document, fn: HandleTableRow): void {
  const trs = document.querySelectorAll(
    '.forumline tbody tr[id], .forumline tbody tr.tCenter',
  ) as NodeListOf<HTMLTableRowElement>

  trs.forEach(tr => fn(tr))
}

export interface FilterOptions {
  filter: string
  hideIgnored: boolean
}

export interface FilterStat {
  total: number
  found: number
  ignored: number
}

// export async function applyActressesFeatures(tr: HTMLTableRowElement): boolean {
//   // TODO: not only boobs
//   const createPill = (text: string) => {
//     const template = document.createElement('template')
//     template.innerHTML = `<span style="font-size: 10px;" class="badge badge-warning badge-pill">${text}</span>`
//     return template.content.children[0]
//   }
//   const textElement = tr.querySelector('.tLink,.tt-text')
//   const { title } = parse(textElement.textContent)
//   const names = parseName(title)
//   const allFakeBoobs = names.every(name => {
//     const profile = storage.getProfile(name)
//     if (!profile || !profile.boobs) {
//       return false
//     }
//     return !!profile.boobs.match(/fake/i)
//   })
//   if (names.length > 0 && allFakeBoobs) {
//     const pill = createPill('Fake boobs')
//     tr.querySelector('a.tt-text').parentElement.appendChild(pill)
//   }
// }

export async function applyFilter(document: Document, options: FilterOptions): Promise<FilterStat> {
  const { ignoredActresses, ignoredGenres, ignoredStudios } = await getSettings()

  const stat: FilterStat = {
    total: 0,
    found: 0,
    ignored: 0,
  }

  // first, reset the view
  handleAllTableRows(document, (tr: HTMLTableRowElement) => {
    tr.style.display = ''
    stat.total++
  })

  // ignoring & filtering & colorizing
  handleAllTableRows(document, (tr: HTMLTableRowElement) => {
    const textElement = tr.querySelector('.tLink,.tt-text')
    const text = textElement?.textContent ?? ''
    if (!textElement || !text) {
      return
    }

    // ignoring
    const ignoredWords = getIgnoredWords(text, ignoredActresses, ignoredGenres, ignoredStudios)
    if (ignoredWords.length > 0) {
      stat.ignored++
      if (options.hideIgnored) {
        tr.style.display = 'none'
        return
      }
      // fade out it
      Array.from(tr.children).forEach(td => td.classList.add('fade-out'))
    }

    // filtering
    const filteredWords = getFilteredWords(text, options.filter)
    if (options.filter && filteredWords.length === 0) {
      tr.style.display = 'none'
      return
    }
    stat.found++

    // colorizing
    textElement.innerHTML = new Colorizer(text)
      .addWords(ignoredWords, true, 'ignore')
      .addWords(filteredWords, false, 'filter')
      .build()
  })

  return stat
}

function getIgnoredWords(
  text: string,
  ignoredActresses: string[],
  ignoredGenres: string[],
  ignoredStudios: string[],
): string[] {
  const actresses = parseName(parseTitle(text))
  const genres = parseGenre(text)
  const studios = parseStudio(text)

  return [
    ...nocaseIntersection(actresses, ignoredActresses),
    ...nocaseIntersection(genres, ignoredGenres),
    ...nocaseIntersection(studios, ignoredStudios),
  ]
}

function getFilteredWords(text: string, filter: string): string[] {
  const words = filter
    .split(';')
    .map(w => w.trim())
    .filter(Boolean)
  const every = words.every(w => text.toLowerCase().includes(w.toLowerCase()))

  return every ? words : []
}
