export function openFile(contentType: string) {
  return new Promise<File | null>(resolve => {
    const input = document.createElement('input')
    input.type = 'file'
    input.accept = contentType
    input.multiple = false

    let resolved = false
    input.onchange = () => {
      if (!resolved) {
        resolved = true
        const files = Array.from(input.files ?? [])
        resolve(files[0]!)
      }
    }

    input.click()
  })
}

export function readFile(file: Blob, encoding: string = 'utf-8'): Promise<string> {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    reader.readAsText(file, encoding)

    reader.onload = () => {
      if (typeof reader.result === 'string') {
        resolve(reader.result)
      } else {
        reject(new Error('Failed to read file as text'))
      }
    }

    reader.onerror = e => reject(e)
  })
}
