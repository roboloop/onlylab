import { format } from 'date-fns'
import filenamify from 'filenamify'
import _ from 'lodash'

export interface Placeholder {
  date: Date
  forum: string
  quality: string
  actresses: string[]
  studios: string[]
}

type Substitute = Record<string, [(p: Placeholder) => string, string, string]>

function sort(array: string[], no: string): string {
  return _.sortBy(array.length ? array : [no], (s: string): string => _.toLower(s)).join(',')
}

export const substitutes: Substitute = {
  '%%': [({}: Placeholder): string => '%', 'Escaping', '%'],
  '%y': [({ date }: Placeholder): string => format(date, 'yy'), 'Year', '01, 24'],
  '%Y': [({ date }: Placeholder): string => format(date, 'yyyy'), 'Year', '2001, 2024'],
  '%m': [({ date }: Placeholder): string => format(date, 'M'), 'Month', '1, 12'],
  '%M': [({ date }: Placeholder): string => format(date, 'MM'), 'Month', '01, 12'],
  '%d': [({ date }: Placeholder): string => format(date, 'd'), 'Day', '9, 21'],
  '%D': [({ date }: Placeholder): string => format(date, 'dd'), 'Day', '09, 21'],
  '%f': [
    ({ forum }: Placeholder): string => forum.split('/')[0].trim() || 'no forum',
    'Forum name',
    'Actresses Clips Packs',
  ],
  '%q': [({ quality }: Placeholder): string => quality || 'no quality', 'Quality', '1080p'],
  '%A': [({ actresses }: Placeholder): string => sort(actresses, 'no actresses'), 'Actresses', 'Sasha Grey'],
  '%S': [({ studios }: Placeholder): string => sort(studios, 'no studios'), 'Studios', 'Brazzers.com'],
} as const

export function interpolate(template: string, placeholder: Placeholder): string {
  let result = template
  for (const key in substitutes) {
    if (substitutes.hasOwnProperty(key)) {
      const regex = new RegExp(key, 'g')
      const [fn] = substitutes[key]
      result = result.replace(regex, filenamify(fn(placeholder), { maxLength: 255 }))
    }
  }

  return result.normalize()
}
