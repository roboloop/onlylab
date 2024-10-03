import _ from 'lodash'

const handlers = []

const register = (key, desc, opts, fn) => {
  handlers.push({
    key,
    desc,
    opts,
    fn(e) {
      const ctrl = (!opts.ctrlKey || e.ctrlKey) && (opts.ctrlKey || !e.ctrlKey)
      const alt = (!opts.altKey || e.altKey) && (opts.altKey || !e.altKey)
      const shift = (!opts.shiftKey || e.shiftKey) && (opts.shiftKey || !e.shiftKey)
      if (ctrl && alt && shift && e.code === key) {
        e.preventDefault()
        fn(e)
      }
    }
  })
}
const unregister = (key, opts) => {
  const index = handlers.findIndex((h) => key === key && _.isEqual(h.opts, opts))
  if (index !== -1) {
    handlers.splice(index, 1)
  }
}

window.addEventListener('keydown', (e) => {
  handlers.forEach(({ fn }) => fn(e))
})

const registered = () => {
  return handlers.filter(({ key, desc, opts }) => ({ key, desc, opts }))
}

export default {
  register,
  unregister,
  registered
}
