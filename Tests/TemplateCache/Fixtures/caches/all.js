'use strict';
var app = angular.module('all', [])
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
  .run(['$templateCache', function ($templateCache) {
    $templateCache.put('a.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    foo a\n' +
    '</div>\n' +
    '');
  }])
  .run(['$templateCache', function ($templateCache) {
    $templateCache.put('b.html',
    '<div class="foo" id=\'bar\'>\n' +
    '    foo b\n' +
    '</div>\n' +
    '');
  }])
;
