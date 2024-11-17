const appName = import.meta.env.VITE_APP_NAME.toLowerCase()
export const baseId = `#${appName}`
export const injectId = `#${appName}-inject`

function createElement(document: Document): HTMLDivElement {
  const element = document.createElement('div')
  element.classList.add(appName)

  return element
}

export function addBasePlace(document: Document): void {
  const element = createElement(document)
  element.setAttribute('id', baseId.replace('#', ''))

  document.body.insertBefore(element, document.body.firstChild)
}

export function addInjectPlace(document: Document): void {
  const element = createElement(document)
  element.setAttribute('id', injectId.replace('#', ''))

  const place = document.querySelector('#page_content')
  if (!place) {
    throw new Error('Cannot add element in DOM')
  }
  place.insertAdjacentElement('beforebegin', element)
  // place.insertBefore(element, place.firstChild)
}
