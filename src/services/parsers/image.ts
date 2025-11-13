import type { ImageLink, ImageNode } from '@/services/dom/topic'

export interface NormalizedImageNode {
  header: string
  imageLinks: ImageLink[]
}

// The goal is to transform ~/services/dom/dom.js:getImages to the representation:
// - No spoiler (7)
// - Screenshots (15)
// - Screenlists (1)
export function normalizeImages(imageNodes: ImageNode[] | ImageNode | null): NormalizedImageNode[] {
  const createNode = (header: string | undefined, imageLinks: ImageLink[]): NormalizedImageNode => ({
    header: header ? header : 'No spoiler',
    imageLinks: imageLinks,
  })

  const recursive = (node: ImageNode): NormalizedImageNode[] => {
    // if it is the spoiler contains only screenshots, then combine these images in one node
    if (
      node.children.length > 0 &&
      node.children.every((c: ImageNode) => c.children.length === 0) &&
      (node.children.every((c: ImageNode) => c.images.length === 1) ||
        node.children.every((c: ImageNode) => c.images.length === 2))
    ) {
      const spoilerNode = createNode(
        node.header,
        node.children.map(c => ({ ...c.images[0]!, header: c.header })),
      )
      return [spoilerNode]
    }

    const flattenNode = createNode(node.header, node.images)

    return [flattenNode, ...node.children.flatMap(c => recursive(c))]
  }

  if (imageNodes === null) {
    return []
  }

  imageNodes = Array.isArray(imageNodes) ? imageNodes : [imageNodes]

  return imageNodes.flatMap(i => recursive(i)).filter(n => n.imageLinks.length > 0)
}
