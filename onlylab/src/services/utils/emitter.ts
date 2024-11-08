import mitt from 'mitt'

type ErrorEvent = {
  msg: string
  err: Error
}

type Events = {
  error: ErrorEvent
}

export default mitt<Events>()
