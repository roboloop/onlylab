const babepediaLink = (name) => {
  const encoded = encodeURIComponent(name)
  return `https://www.babepedia.com/babe/${encoded}`
}

const trackerSearchLink = (content) => {
  const encoded = encodeURIComponent(content)
  return `https://ptzkpdek.hct/forum/tracker.php?nm=%22${encoded}%22`
}

export default {
  babepediaLink,
  trackerSearchLink
}
