import client from './../clients'

export default {
  async do(title, href) {
    if (!href) {
      return title
    }

    try {
      const data = await client.get(href)
      const matched = data.match(/src="(https:\/\/cdn-images[^"]+)/)
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
    return !!title.match(/[/.]imagevenue\.com[/.]/)
  }
}
