import engine from 'store/src/store-engine'
import localStorage from 'store/storages/localStorage'
import memoryStorage from 'store/storages/memoryStorage'
import json2 from 'store/plugins/json2'
const localStore = engine.createStore([localStorage], [json2])
const memoryStore = engine.createStore([memoryStorage], [json2])
const store = localStore.enabled ? localStore : memoryStore

const isEnabled = () => {
  return localStore.enabled
}

const removeWithPrefix = (prefix) => {
  store.each((val, key) => {
    if (key.startsWith(prefix)) {
      store.remove(key)
    }
  })
}

const profilePrefix = 'profile:'
const profileKey = (name) => {
  return profilePrefix + name
}
const putProfile = (name, profile) => {
  store.set(profileKey(name), profile)
}
const getProfile = (name) => {
  return store.get(profileKey(name))
}
const removeProfiles = () => {
  removeWithPrefix(profilePrefix)
}

const imgPrefix = 'img:'
const imgKey = (title) => {
  return imgPrefix + title
}
const putImg = (title, href) => {
  store.set(imgKey(title), href)
}
const getImg = (title) => {
  return store.get(imgKey(title))
}
const removeImg = (title) => {
  store.remove(imgKey(title))
}
const removeImgs = () => {
  removeWithPrefix(imgPrefix)
}

const filterKey = () => {
  return 'filter'
}
const putFilter = (value) => {
  store.set(filterKey(), value)
}
const getFilter = () => {
  return store.get(filterKey())
}

const downloadedPrefix = 'downloaded:'
const downloadedKey = (topic) => {
  return 'downloaded:' + topic
}
const putDownloaded = (topic) => {
  const downloadedAt = new Date().toISOString()
  store.set(downloadedKey(topic), downloadedAt)
}
const getDownloaded = (topic) => {
  return store.get(downloadedKey(topic))
}
const removeDownloaded = (topic) => {
  store.remove(downloadedKey(topic))
}

const removeAll = () => {
  store.clearAll()
}

const stat = () => {
  let profiles = 0,
    images = 0,
    downloads = 0,
    total = 0
  store.each((val, key) => {
    const size = key.length + JSON.stringify(val).length
    if (key.startsWith(profilePrefix)) {
      profiles += size
    } else if (key.startsWith(imgPrefix)) {
      images += size
    } else if (key.startsWith(downloadedPrefix)) {
      downloads += size
    }
    total += size
  })

  return {
    total,
    profiles,
    images,
    downloads
  }
}

const readAll = () => {
  const data = {}
  store.each((val, key) => {
    data[key] = val
  })
  return data
}

const writeAll = (data) => {
  store.clearAll()
  for (const key in data) {
    store.set(key, data[key])
  }
}

export default {
  putProfile,
  getProfile,
  removeProfiles,

  putImg,
  getImg,
  removeImg,
  removeImgs,

  putFilter,
  getFilter,

  putDownloaded,
  getDownloaded,
  removeDownloaded,

  removeAll,

  isEnabled,
  stat,
  readAll,
  writeAll
}
