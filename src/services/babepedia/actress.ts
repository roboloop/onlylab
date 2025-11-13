import _ from 'lodash'
import * as babepedia from '@/services/clients/babepedia'

// To be clear
// [uratjn.zzs] ifectcsg yabgetk (cpe lwmrozhpf)
//              ↑ "Main name"         ↑ "Name"
//
// "Babe name" (the first name from babepedia.com): cpdmjio nyzhvfr

import type { Profile } from '@/services/store/profiles'

export interface NormalizedProfile extends Profile {
  mainName: string
  aliases: string[]
}

export async function normalizeProfiles(groupedNames: string[][]): Promise<NormalizedProfile[]> {
  const promises = groupedNames
    .filter(g => g.length)
    .map(async names => {
      const promises = names.map(n => babepedia.profile(n))
      // TODO: error handling
      const profiles = await Promise.all(promises)

      const mainName = names[0]!
      const [nonNullGroup, nullGroup] = _.partition(profiles, p => !!p.babeName)
      const entries = Object.entries(_.groupBy(nonNullGroup, p => p.babeName))
      const groupFn: (arg: [string, Profile[]]) => [string, Profile[]] = ([n, p]) => [n, [...p, ...nullGroup]]
      const grouped = nonNullGroup.length ? entries.map(groupFn) : Object.entries({ [mainName]: nullGroup })

      return grouped.map(([babeName, profiles]) => {
        const profile = profiles.find(p => p.babeName) ?? profiles[0]
        return {
          mainName: mainName,
          aliases: _.uniq([...profiles, { name: babeName }].map(p => p.name).filter(n => n !== mainName)),
          ...profile,
        } as NormalizedProfile
      })
    })

  return (await Promise.all(promises)).flat()
}
