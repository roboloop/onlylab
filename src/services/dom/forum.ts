import { parseGenre } from '@/services/parsers/genre'
import { parseName } from '@/services/parsers/name'
import { parseStudio } from '@/services/parsers/studio'
import { parseTitle } from '@/services/parsers/title'
import { getProfile } from '@/services/store/profiles'
import { getSettings } from '@/services/store/settings'
import { nocaseIntersection } from '@/services/utils/array'
import { Colorizer } from '@/services/utils/colorizer'
import { extractFilteredWords } from '@/services/utils/filter'

import type { Profile } from '@/services/store/profiles'

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

export async function applyFilter(document: Document, options: FilterOptions): Promise<FilterStat> {
  const { ignored } = await getSettings()
  const { actresses: ignoredActresses, genres: ignoredGenres, studios: ignoredStudios } = ignored

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
    const filteredWords = extractFilteredWords(text, options.filter)
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

export async function addPills(document: Document): Promise<void> {
  const { babepedia } = await getSettings()
  if (!babepedia.enable) {
    return
  }

  const addPill = (tr: HTMLTableRowElement, text: string) => {
    const template = document.createElement('template')
    template.innerHTML = `<span class="actress-pill">${text}</span>`
    const pill = template.content.children[0]!
    tr.querySelector('a.tt-text')?.parentElement?.appendChild(pill)
  }

  handleAllTableRows(document, async (tr: HTMLTableRowElement) => {
    const textElement = tr.querySelector('.tLink,.tt-text')
    const text = textElement?.textContent ?? ''
    if (!textElement || !text) {
      return
    }

    const actresses = parseName(parseTitle(text))
    const profiles: Profile[] = []

    for (const actress of actresses) {
      const profile = await getProfile(actress)
      if (!profile || !profile.babeName) {
        // skip if there is no profile or the saved profile is dummy
        continue
      }
      profiles.push(profile)
    }

    const fakeBoobs = profiles.length && profiles.some(p => p.boobs?.match(/fake/i))
    if (babepedia.badges.fakeBoobs && fakeBoobs) {
      addPill(tr, 'Fake boobs')
    }

    const tattoos = profiles.length && profiles.some(p => p.tattoos)
    if (babepedia.badges.tattoos && tattoos) {
      addPill(tr, 'Tattoos')
    }

    const piercings = profiles.length && profiles.some(p => p.piercings)
    if (babepedia.badges.piercings && piercings) {
      addPill(tr, 'Piercings')
    }
  })
}
