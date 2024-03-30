import client from './../clients'

export default {
  async do(title, href) {
    if (!href) {
      return title
    }

    try {
      const data = await client.get(href, { Accept: 'text/html', 'User-Agent': 'curl/8.4.0' })
      const matched = data.match(/src="(.+?fastpic\.org\/big\/.+?)"/)
      if (matched) {
        return matched[1]
      }
    } catch (error) {
      // TODO: good processing?
      console.log('Fail to load:', href)
    }

    return title
  },

  support(title) {
    return !!title.match(/[/.]fastpic\.org[/.]/)
  }
}
