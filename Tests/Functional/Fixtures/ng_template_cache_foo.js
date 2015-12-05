'use strict';

angular.module('foo.templates')
  .run(['$templateCache', function ($templateCache) {
    $templateCache.put('bar.html',
    'bar\n' +
    '');
    $templateCache.put('bar/baz.html',
    'bar/baz\n' +
    '');
    $templateCache.put('foo.html',
    'foo\n' +
    '');
  }])
;
