'use strict';

angular.module('bar')
  .run(['$templateCache', function ($templateCache) {
    $templateCache.put('c.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    bar c\n' +
    '</div>\n' +
    '');
  }])
  .run(['$templateCache', function ($templateCache) {
    $templateCache.put('d.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    bar d\n' +
    '</div>\n' +
    '');
  }])
;
