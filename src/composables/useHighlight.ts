import { type Directive, type DirectiveBinding } from 'vue'

export function useHighlight(className: string) {
  const EVENT_NAME = 'click'
  const elements: [HTMLElement, () => void][] = []

  const vHighlight: Directive = {
    mounted(el: HTMLElement, binding: DirectiveBinding) {
      const clickListener = () => {
        elements.map(([e]) => e.classList.remove(className))

        el.classList.add(className)
      }
      if (binding.modifiers.init) {
        el.classList.add(className)
      }

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

  return { vHighlight }
}
