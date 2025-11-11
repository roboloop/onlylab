import { store as imagesStore } from './images'
import { store as profilesStore } from './profiles'
import { store as settingsStore } from './settings'
import { store as forumStore } from './state'
import { store as torrentStore } from './torrent'

export function localStoreSize() {
  let forum = 0,
    images = 0,
    profiles = 0,
    settings = 0,
    torrent = 0
  const forumKey = forumStore.config().name! + '/'
  const imagesKey = imagesStore.config().name! + '/'
  const profilesKey = profilesStore.config().name! + '/'
  const settingsKey = settingsStore.config().name! + '/'
  const torrentKey = torrentStore.config().name! + '/'

  const length = window.localStorage.length
  for (let i = 0; i < length; i++) {
    const key = localStorage.key(i) ?? ''
    const value = localStorage.getItem(key) ?? ''
    const size = key.length + JSON.stringify(value).length

    if (key.startsWith(imagesKey)) {
      images += size
    } else if (key.startsWith(forumKey)) {
      forum += size
    } else if (key.startsWith(profilesKey)) {
      profiles += size
    } else if (key.startsWith(settingsKey)) {
      settings += size
    } else if (key.startsWith(torrentKey)) {
      torrent += size
    }
  }

  return {
    forum,
    images,
    profiles,
    settings,
    torrent,
  }
}
