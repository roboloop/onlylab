import client from '../clients/client.js'

export default {
  async do(title, href) {
    if (!href) {
      return title
    }

    try {
      const data = await client.send({
        url: href
        // redirect: 'manual',
        // overrideMimeType: 'text/html',
        // headers: {
        //   accept: 'text/html',
        //   'User-Agent': 'curl/8.4.0'
        // }
      })
      const matched = data.match(/src="([^"]+)".+main-image/)
      if (matched) {
        return matched[1]
      }
    } catch (error) {
      console.log(`Fail to load ${href}`, error)
    }

    return title
  },

  support(title) {
    return !!title.match(/[/.]imagebam\.com[/.]/)
  }
}
