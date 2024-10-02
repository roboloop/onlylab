// The goal is to transform @/src/services/dom/dom.js:getImages to the representation:
//
// - Topic images (23)
// - No spoiler (7)
// - Screenshots (15)
// - Screenlists (1)
// - Comment images (all)
// - Screenshots (15)
const normalize = (images) => {
  let id = 0
  const createNode = (header, images) => ({
    id: ++id,
    header: header ? header : 'No spoiler',
    images: images
  })
  const recursive = (node) => {
    // if it is the spoiler contain only screenshots, then combine these images in one node
    if (
      node.children.length > 0 &&
      node.children.every((c) => c.children.length === 0 && c.images.length === 1)
    ) {
      return createNode(
        node.header,
        node.children.map((c) => ({ ...c.images[0], header: c.header }))
      )
    }

    const flattenNode = createNode(node.header, node.images)

    return [flattenNode, ...node.children.flatMap((c) => recursive(c))]
  }

  images = Array.isArray(images) ? images : [images]
  const [topicImages, ...commentImages] = images

  const topic = recursive(topicImages)
  const comment = commentImages.flatMap((i) => recursive(i))

  return [
    createNode(
      'Topic images',
      topic.flatMap((n) => n.images)
    ),
    ...topic,
    createNode(
      'Comment images',
      comment.flatMap((n) => n.images)
    ),
    ...comment
  ].filter((n) => n.images.length > 0)
}

export default {
  normalize
}
