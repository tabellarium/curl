<?php

declare(strict_types=1);

namespace Tabellarium\Curl;

final class CurlVersion
{
    private function __construct()
    {
    }

    /**
     * Returns cURL 24 bit version number.
     *
     * @return int
     */
    public static function getCurlVersionNumber(): int
    {
        return \curl_version()['version_number'];
    }

    /**
     * Returns cURL version number, as string.
     *
     * @return string
     */
    public static function getCurlVersion(): string
    {
        return \curl_version()['version'];
    }

    /**
     * Returns OpenSSL 24 bit version number.
     *
     * @return int
     */
    public static function getSslVersionNumbner(): int
    {
        return \curl_version()['ssl_version_number'];
    }

    /**
     * Returns OpenSSL version number, as string.
     *
     * @return string
     */
    public static function getSslVersion(): string
    {
        return \curl_version()['ssl_version'];
    }

    /**
     * Returns zlib version number, as string.
     *
     * @return string
     */
    public static function getLibzVersion(): string
    {
        return \curl_version()['libz_version'];
    }
}
