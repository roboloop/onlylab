export function parseHumanSize(size: string): number | undefined {
  const units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB']
  const match = size.match(/(\d+([.,]\d+)?)\s*(\w+)/)

  if (!match) {
    return undefined
  }

  const value = parseFloat(match[1].replace(',', '.'))
  const unit = match[3].toUpperCase().replace('I', '')

  const exponent = units.indexOf(unit)
  if (exponent === -1) {
    return undefined
  }

  return value * Math.pow(1024, exponent)
}
