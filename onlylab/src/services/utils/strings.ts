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

type Substitute = Record<string, (p: Placeholder) => string>

function sort(array: string[], no: string): string {
  return _.sortBy(array.length ? array : no, (s: string): string => _.toLower(s)).join(',')
}

const substitute: Substitute = {
  '%%': ({}: Placeholder): string => '%',
  '%y': ({ date }: Placeholder): string => format(date, 'yy'),
  '%Y': ({ date }: Placeholder): string => format(date, 'yyyy'),
  '%m': ({ date }: Placeholder): string => format(date, 'M'),
  '%M': ({ date }: Placeholder): string => format(date, 'MM'),
  '%d': ({ date }: Placeholder): string => format(date, 'd'),
  '%D': ({ date }: Placeholder): string => format(date, 'dd'),
  '%f': ({ forum }: Placeholder): string => forum.split('/')[0].trim() || 'no forum',
  '%q': ({ quality }: Placeholder): string => quality || 'no quality',
  '%A': ({ actresses }: Placeholder): string => sort(actresses, 'no actresses'),
  '%S': ({ studios }: Placeholder): string => sort(studios, 'no studios'),
}

export function interpolate(template: string, placeholder: Placeholder): string {
  let result = template
  for (const key in substitute) {
    if (substitute.hasOwnProperty(key)) {
      const regex = new RegExp(key, 'g')
      result = result.replace(regex, filenamify(substitute[key](placeholder), { maxLength: 255 }))
    }
  }

  return result.normalize()
}
