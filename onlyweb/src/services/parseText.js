import { parseGenre } from './parsers/genre'
import { parseQuality } from './parsers/quality'
import { parseStudio } from './parsers/studio'
import { parseTitle } from './parsers/title'
import { parseYear } from './parsers/year'

export function parseText(raw) {
  return {
    title: parseTitle(raw),
    genres: parseGenre(raw),
    studious: parseStudio(raw),
    quality: parseQuality(raw),
    year: parseYear(raw)
  }
}
