import { parseTitle } from './title.js'
import { parseGenre } from './genre.js'
import { parseStudio } from './studio.js'
import { parseQuality } from './quality.js'
import { parseYear } from './year.js'

export function parse(raw) {
  return {
    title: parseTitle(raw),
    genres: parseGenre(raw),
    studious: parseStudio(raw),
    quality: parseQuality(raw),
    year: parseYear(raw)
  }
}
