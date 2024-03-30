import client from './../clients'

export default {
  async do(title, href) {
    if (!href) {
      return title
    }

    try {
      const data = await client.get(href)
      const matched = data.match(/<meta property="og:image" content="([^"]+)"/)
      if (matched) {
        return matched[1]
      }
    } catch (error) {
      // TODO: good processing?
      console.log('Fail to load:', href)
    }

    return title
  },

  support(title, href) {
    const regex = /[/.]turboimagehost\.com\/|turboimg\.net\//
    return !!(title.match(regex) || (href && href.match(regex)))
  }
}
