import client from './../clients'

export default {
  async do(title, href) {
    if (!href) {
      return title
    }

    try {
      href = href.replace(/^https?/, 'https')
      if (href.match(/\.jpe?g$/)) {
        return href
      }

      const data = await client.send({
        url: href, redirect: 'manual', overrideMimeType: 'text/html', headers: {
          accept: 'text/html',
          'User-Agent': 'curl/8.4.0',
        }
      })
      const matched = data.match(/src="(.+?fastpic\.org\/big\/.+?)"/)
      if (matched) {
        return matched[1]
      }
    } catch (error) {
      // TODO: good processing?
      console.log(`Fail to load ${href}`, error)
    }

    return title
  },

  support(title) {
    return !!title.match(/[/.]fastpic\.org[/.]/)
  }
}
