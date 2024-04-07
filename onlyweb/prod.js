import { readFileSync, writeFileSync } from 'fs'

const tampermonkey = readFileSync('tampermonkey.js.template', 'utf8');
const js = readFileSync('dist/assets/index.js')
const css = readFileSync('dist/assets/index.css')

const [first, second, third] = tampermonkey.split(/(?:<%= css %>)|(?:<%= js %>)/)
const replaced = first + css + second + js + third
writeFileSync('tampermonkey/tampermonkey.js', replaced, 'utf8');