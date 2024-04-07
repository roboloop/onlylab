/* eslint-disable */

// ==UserScript==
// @name         OnlyWeb Dev
// @namespace    http://localhost/
// @version      0.1
// @description  try to take over the world!
// @author       You
// @match        https://tracker.net/forum/*
// @icon         https://www.google.com/s2/favicons?sz=64&domain=mozilla.org
// @connect      *
// @grant        GM_xmlhttpRequest
// @grant        GM_getResourceText
// @grant        GM_addElement
// @grant        GM_addStyle
// @grant        unsafeWindow
// @run-at       document-end
// ==/UserScript==

(async function () {
  'use strict'
  const cssUrl = 'http://localhost/assets/index.css'
  const jsUrl = 'http://localhost/assets/index.js'

  function log(...args) {
    console.log('[OnlyWeb]', ...args)
  }

  async function addCss() {
    const response = await GM.xmlHttpRequest({ url: cssUrl }).catch((e) => log(e))
    const css = response.responseText
    GM_addStyle(css)
  }

  async function addJs() {
    const response = await GM.xmlHttpRequest({ url: jsUrl }).catch((e) => log(e))
    const js = response.responseText
    eval(js)
    return
    const element = document.createElement('script')
    element.textContent = js
    element.setAttribute('type', 'module')
    element.setAttribute('crossorigin', '')
    document.body.appendChild(element)
  }

  function addApp() {
    const element = document.createElement('div')
    element.setAttribute('id', 'app')
    document.body.insertBefore(element, document.body.firstChild)
    app.mount('#app')
  }

  await addCss()
  await addJs()
  addApp()
})()
