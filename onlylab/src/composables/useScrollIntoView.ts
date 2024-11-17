import type { Directive } from 'vue'

// TODO: similar code to useHighlight
export function useScrollIntoView(element: Element) {
  const EVENT_NAME = 'click'
  const elements: [HTMLElement, () => void][] = []

  const vScrollIntoView: Directive = {
    mounted(el: HTMLElement) {
      const clickListener = () => element.scrollIntoView()

      el.addEventListener(EVENT_NAME, clickListener)
      elements.push([el, clickListener])
    },
    beforeUnmount(el: HTMLElement) {
      const index = elements.findIndex(([e]) => e === el)
      if (index !== -1) {
        const [, listener] = elements[index]
        el.removeEventListener(EVENT_NAME, listener)
        elements.splice(index, 1)
      }
    },
  }

  return { vScrollIntoView }
}
