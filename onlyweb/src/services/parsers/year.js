export function parseYear(original) {
  // Search patterns like 1999, 2023, 2023-2024, 2023 - 2024
  // Exclude matches that have another date like 2024-07-30
  let matched = original.match(
    /\D((?:19|20)[0-9][0-9](?!-[0-9][0-9]-)(\s*-\s*(19|20)[0-9][0-9])?)\D/
  )

  return matched ? matched[1] : ''
}
