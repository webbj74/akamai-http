<?php

/**
 * Examine the values of Akamai-specific HTTP Headers
 */

namespace Akamai\Http;

use \Guzzle\Http\StaticClient;

class AkamaiHeader
{
    /**
     * Returns the value of the X-True-Cache-Key header on the given page.
     *
     * @param string $url
     * @param array $options
     * @return string
     */
    public static function getAkamaiTrueCacheKey($url, $options = array())
    {
        return self::getAkamaiHeader($url, 'True-Cache-Key', $options);
    }

    /**
     * Return the requested Akamai Debugging Header
     * TODO: Stop using external call to curl for this
     *
     * @param string $url
     * @param string $parameter
     * @param array|null $options
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function getAkamaiHeader($url, $parameter, $options = array())
    {
        if (!is_string($url)) {
            throw new \InvalidArgumentException(sprintf('%s: Expected $url to be a string.', __METHOD__));
        }
        if (!is_string($parameter)) {
            throw new \InvalidArgumentException(sprintf('%s: Expected $parameter to be a string.', __METHOD__));
        }
        if (!is_array($options)) {
            throw new \InvalidArgumentException(sprintf('%s: Expected $options to be an array.', __METHOD__));
        }

        if (!isset($options['headers'])) {
            $options['headers'] = array();
        }

        $options['headers']['Pragma'] = 'Akamai-X-Get-' . $parameter;
        $result = StaticClient::get($url, $options);
        return (string) $result->getHeader('X-' . $parameter);
    }
}
