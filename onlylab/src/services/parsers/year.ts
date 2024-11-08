export function parseYear(original: string): string {
  // Search patterns like 1999, 2023, 2023-2024, 2023 - 2024
  // Exclude matches that have another date like 2024-07-30
  const matched = original.match(/\D((?:19|20)[0-9][0-9](?!-[0-9][0-9]-)(\s*-\s*(19|20)[0-9][0-9])?)\D/)

  if (matched) {
    return matched[1]
  }

  // Try to extract year from date like 2024-07-30
  const matched2 = original.match(/\D((?:19|20)[0-9][0-9])-[0-9][0-9]-[0-9][0-9]\D/)

  return matched2 ? matched2[1] : ''
}
