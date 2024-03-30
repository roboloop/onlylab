export function parseStudio(original) {
  const matched = original.match(/^\s*\[([a-zA-Z0-9/\\.\s_()\-!']+?)\]/)
  if (!matched) {
    return []
  }
  const splitted = matched[1].split(/[\\/]/).filter(Boolean)
  const withAliases = splitted.map((s) => s.trim()).filter((s) => s)
  const regex = /([a-zA-Z0-9_\-.'\s]*)\s*(?:\(([a-zA-Z0-9_\-.'\s]*)\))*/
  const notFlatten = withAliases.map((a) => a.match(regex)[1] ?? '').filter(Boolean)

  return notFlatten
}
