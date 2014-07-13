'use strict';

angular.module('all')
  .run(['$templateCache', function ($templateCache) {
    $templateCache.put('c.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    bar c\n' +
    '</div>\n' +
    '');
    $templateCache.put('d.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    bar d\n' +
    '</div>\n' +
    '');
    $templateCache.put('a.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    foo a\n' +
    '</div>\n' +
    '');
    $templateCache.put('b.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    foo b\n' +
    '</div>\n' +
    '');
  }])
;
