export default {
  async send(opts) {
    // TODO: console.log error handle
    // eslint-disable-next-line no-undef
    const response = await GM.xmlHttpRequest(opts).catch((e) => console.log(e))
    return response.responseText
  },

  async sendBlob(opts) {
    // TODO: console.log error handle
    // eslint-disable-next-line no-undef
    const response = await GM.xmlHttpRequest(opts).catch((e) => console.log(e))
    return response.response
  }
}
