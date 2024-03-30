export default {
  async get(url, headers = {}) {
    // TODO: console.log error handle
    // eslint-disable-next-line no-undef
    const response = await GM.xmlHttpRequest({ url, headers }).catch((e) => console.log(e))
    return response.responseText
  }
}
