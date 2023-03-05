// ==UserScript==
// @name         onlytracker open tab
// @namespace    http://tampermonkey.net/
// @version      0.1
// @description  try to take over the world!
// @author       You
// @include      /.*tracker\.net.*/
// @icon         https://www.google.com/s2/favicons?sz=64&domain=mozilla.org
// @grant        none
// ==/UserScript==

(function() {
  'use strict';

  $('table tbody > tr > td > div').on('click', 'a', function(e) {
    const matched = this.href.match(/\/viewtopic.php\?t=(\d+)/);
    if (!matched && !e.altKey) {
      return true;
    }

    const topicId = matched[1]
    const link = 'https://873.332.933.331/topics/' + topicId;

    window.open(link, '_blank').focus();

    return false;
  });
})();
