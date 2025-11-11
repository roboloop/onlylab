import { vi } from 'vitest'

import type { Client } from '../client'

export const client: Client = {
  send: vi.fn(),
  sendBlob: vi.fn(),
}
