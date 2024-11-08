import escapeStringRegexp from 'escape-string-regexp'

export class Colorizer {
  private readonly colorized: string[]
  private readonly noneColor: string = 'none'
  constructor(private readonly text: string) {
    this.colorized = new Array(text.length).fill(this.noneColor)
  }

  addWords(words: string[], boundary: boolean, color: string): this {
    if (color === this.noneColor) {
      throw new Error(`${color} is reserved color`)
    }

    const escaped = words.map(w => escapeStringRegexp(w)).join('|')
    const pattern = boundary ? `\\b(${escaped})\\b` : `(${escaped})`
    const regex = new RegExp(pattern, 'gi')

    for (const match of this.text.matchAll(regex)) {
      this.colorized.fill(color, match.index, match.index + match[0].length)
    }

    return this
  }

  build(): string {
    let result = ''
    let previousColor = this.noneColor

    for (let i = 0; i < this.text.length; i++) {
      if (previousColor === this.colorized[i]) {
        result += this.text[i]
        continue
      }

      if (previousColor !== this.noneColor) {
        result += '</span>'
      } else {
        result += `<span class="${this.colorized[i]}">`
      }

      previousColor = this.colorized[i]
      result += this.text[i]
    }
    if (previousColor !== this.noneColor) {
      result += '</span>'
    }

    return result
  }
}
