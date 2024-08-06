export default {
  do(title) {
    const isFull = title.match(/o\.[^.]+$/)
    if (isFull) {
      return title
    }

    const step1 = title.replace(/(?<=\/\/)thumbs/, 'images')
    if (step1 === title) {
      return title
    }
    const step2 = step1.replace(/_t(?=\.\w+$)/, '_o')
    if (step2 === step1) {
      return title
    }

    return step2
  },

  support(title) {
    return !!title.match(/[/.]imgbox\.com[/.]/)
  }
}
