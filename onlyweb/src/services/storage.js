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

const profileKey = (name) => {
  return 'profile:' + name
}
const putProfile = (name, profile) => {
  store.set(profileKey(name), profile)
}
const getProfile = (name) => {
  return store.get(profileKey(name))
}

const imgKey = (title) => {
  return 'img:' + title
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

const filterKey = () => {
  return 'filter'
}
const putFilter = (value) => {
  store.set(filterKey(), value)
}
const getFilter = () => {
  return store.get(filterKey())
}

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

export default {
  putProfile,
  getProfile,

  putImg,
  getImg,
  removeImg,

  putFilter,
  getFilter,

  putDownloaded,
  getDownloaded,
  removeDownloaded,

  isEnabled
}
