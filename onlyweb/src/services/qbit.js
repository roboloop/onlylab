import client from './clients'

const qbitBaseUrl = import.meta.env.VITE_QBIT_BASE_URL
const addEndpoint = '/api/v2/torrents/add'

export default {
  async upload(downloadLink, folder, paused) {
    const raw = await client.sendBlob({ url: downloadLink, responseType: 'blob' })
    const blob = new Blob([raw])
    const file = new File([blob], 'name.torrent', { type: 'application/x-bittorrent' })
    const formData = new FormData()
    formData.append('torrents', file)
    formData.append('autoTMM', 'false')
    formData.append('rename', '')
    formData.append('paused', String(paused))
    formData.append('savepath', '/home/media/' + folder)

    const response = await client
      .send({ method: 'POST', url: qbitBaseUrl + addEndpoint, data: formData })
      .catch((e) => console.log(e))

    return response === 'Ok.'
  }
}
