import PQueue from 'p-queue'

const MAX_CONCURRENCY = 4
const STEP_LOADED = 20

// TODO: update a comment
// The goal of loader is making as many calls as fit on one page. By default, is 20.
// These calls are executed simultaneously, but with a limit - max 4 at the time (via pLimit).
// There is an inner counter that is responsible for loading the next page.
//
// Also, there is a feature — that allows to perform prefetch of the next page if the index is close to it.
//
// Page 1:
// - call1 [index 0]
// - call2 [index 1]
// - ...
// - call20 [index 19]
// Page 2:
// - call4 [index 3]
export function usePagination() {
  let calls = 0
  let limitLoaded = STEP_LOADED

  const queue = new PQueue({
    concurrency: MAX_CONCURRENCY,
    autoStart: true,
  })

  queue.on('active', () => {
    if (+calls + 1 >= limitLoaded) {
      queue.pause()
    }
    calls++
  })

  function reset(): void {
    calls = 0
    limitLoaded = STEP_LOADED
    queue.clear()
    queue.start()
  }

  async function addCall(fn: () => void): Promise<void> {
    queue
      .add(() => fn())
      .catch(err => {
        // TODO:
        console.log('err', err)
      })
  }

  function nextPageIfNeeded(index: number): void {
    if (index >= limitLoaded - STEP_LOADED / 2) {
      limitLoaded = Math.floor((index + STEP_LOADED * 2 - 1) / STEP_LOADED) * STEP_LOADED
      queue.start()
    }
  }

  return {
    reset,
    addCall,
    nextPageIfNeeded,
  }
}
