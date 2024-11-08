export interface Client {
  send(opts: Tampermonkey.Request): Promise<string>
  sendBlob(opts: Tampermonkey.Request): Promise<Blob>
}

class TampermonkeyClient implements Client {
  async send(opts: Tampermonkey.Request): Promise<string> {
    try {
      const response = await GM.xmlHttpRequest(opts)
      return response.responseText
    } catch (error) {
      console.error('send', error)
      throw new Error('xmlHttpRequest thrown an error')
    }
  }

  async sendBlob(opts: Tampermonkey.Request): Promise<Blob> {
    try {
      const response = await GM.xmlHttpRequest(opts)
      return response.response
    } catch (error) {
      console.error('sendBlob', error)
      throw new Error('xmlHttpRequest thrown an error')
    }
  }
}

export const client: Client = new TampermonkeyClient()
