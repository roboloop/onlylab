import client from './client'

const qbitBaseUrl = import.meta.env.VITE_QBIT_BASE_URL
const qbitSavePath = import.meta.env.VITE_QBIT_SAVE_PATH
const addEndpoint = '/api/v2/torrents/add'
const listEndpoint = '/api/v2/torrents/info'
const deleteEndpoint = '/api/v2/torrents/delete'

export default {
  async upload(topic, downloadLink, folder, paused) {
    const raw = await client.sendBlob({ url: downloadLink, responseType: 'blob' })
    const blob = new Blob([raw])
    const file = new File([blob], 'name.torrent', { type: 'application/x-bittorrent' })
    const formData = new FormData()
    formData.append('torrents', file)
    formData.append('autoTMM', 'false')
    formData.append('rename', '')
    formData.append('paused', String(paused))
    formData.append('savepath', qbitSavePath + folder)
    formData.append('tags', topic)

    const response = await client
      .send({ method: 'POST', url: qbitBaseUrl + addEndpoint, data: formData })
      .catch((e) => console.log(e))

    return response === 'Ok.'
  },

  async list() {
    const params = new URLSearchParams({
      filter: 'all'
    })
    const response = await client
      .send({ method: 'GET', url: qbitBaseUrl + listEndpoint + '?' + params.toString() })
      .catch((e) => console.log(e))

    return JSON.parse(response)
  },

  async get(topic) {
    return (await this.list()).find((t) => t.tags === topic)
  },

  async delete(id) {
    const topic = await this.get(id)
    if (!topic) {
      return false
    }

    const statuses = [
      'error',
      'allocating',
      'downloading',
      'metaDL',
      'pausedDL',
      'queuedDL',
      'stalledDL'
    ]
    const deleteFiles = statuses.includes(topic.state)

    const params = new URLSearchParams({
      hashes: topic.hash,
      deleteFiles: String(deleteFiles)
    })
    await client
      .send({ method: 'GET', url: qbitBaseUrl + deleteEndpoint + '?' + params.toString() })
      .catch((e) => console.log(e))

    return true
  }
}
