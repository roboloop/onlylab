// TODO: performance issue
export async function storeSize(store: LocalForage): Promise<number> {
  let total = 0
  await store.iterate((value, key) => {
    total += key.length + JSON.stringify(value).length
  })
  const totalKeys = await store.length()
  const { name } = store.config()
  return total + totalKeys * `${name}/`.length
}
