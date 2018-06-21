<?php

include 'vendor/autoload.php';

use Akamai\Http\AkamaiHeader;

$url = $argv[1];

try {
    $trueCacheKey = AkamaiHeader::getAkamaiTrueCacheKey($url);
    $output = "URL did not return X-True-Cache-Key.\n";
    if (!empty($trueCacheKey)) {
        $output = sprintf("The X-True-Cache-Key for '$url' is '%s'\n", $url, $trueCacheKey);
    }
    echo $output;
} catch (\Exception $e) {
    $error = preg_replace('/[\r\n]*/', '', $e->getMessage());
    fprintf(STDERR, "AkamaiHeader::getAkamaiTrueCacheKey: %s\n", $error);
}

