import _ from 'lodash'

export function nocaseIntersection(first: string[], second: string[]): string[] {
  return _.intersection(
    first.map(a => a.toLowerCase()),
    second.map(a => a.toLowerCase()),
  )
}

export function localSort(first: string[]): string[] {
  return first.slice().sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }))
}
