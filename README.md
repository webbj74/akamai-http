akamai-http
===========

PHP-based library for interfacing with Akamai-fronted websites.

[![Build Status](https://travis-ci.org/webbj74/akamai-http.png?branch=master)](https://travis-ci.org/webbj74/akamai-http) [![Coverage Status](https://coveralls.io/repos/github/webbj74/akamai-http/badge.svg?branch=master)](https://coveralls.io/github/webbj74/akamai-http?branch=master) 

## Sample Usage

```
use Akamai\Http\AkamaiHeader;

// ...

try {
    $trueCacheKey = AkamaiHeader::getAkamaiTrueCacheKey($url);
    if (!empty($akamai)) {
        echo sprintf("The X-True-Cache-Key for '$url' is '%s'\n", $url, $akamai);
    }
} catch (\Exception $e) {
    $error = preg_replace('/[\r\n]*/', '', $e->getMessage());
    fprintf(STDERR, "AkamaiHeader::getAkamaiTrueCacheKey: %s\n", $error);
}
```
