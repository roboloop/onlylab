const fromHuman = (size) => {
  const units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB']
  const match = size.match(/(\d+([.,]\d+)?)\s*(\w+)/)

  if (!match) throw new Error('Invalid size format')

  const value = parseFloat(match[1].replace(',', '.'))
  const unit = match[3].toUpperCase().replace('I', '')

  const exponent = units.indexOf(unit)
  if (exponent === -1) throw new Error('Invalid unit')

  return value * Math.pow(1024, exponent)
}

export default {
  fromHuman
}
