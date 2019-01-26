<?php

declare(strict_types=1);

namespace Tabellarium\Curl;

/**
 * Not all constants are defined in PHP.
 *
 * @see https://github.com/curl/curl/blob/master/include/curl/curl.h
 */
final class CurlFeature
{
    public const IPV6 = 1;                 // 2^0  - IPv6-enabled
    public const KERBEROS4 = 2;            // 2^1  - Kerberos V4 auth is supported
    public const SSL = 4;                  // 2^2  - SSL options are present
    public const LIBZ = 8;                 // 2^3  - libz features are present
    public const NTLM = 16;                // 2^4  - NTLM auth is supported
    public const GSSNEGOTIATE = 32;        // 2^5  - Negotiate auth is supported
    public const DEBUG = 64;               // 2^6  - Built with debug capabilities
    public const ASYNCHDNS = 128;          // 2^7  - Asynchronous DNS resolves
    public const SPNEGO = 256;             // 2^8  - SPNEGO auth is supported
    public const LARGEFILE = 512;          // 2^9  - Supports files larger than 2GB
    public const IDN = 1024;               // 2^10 - Internationized Domain Names are supported
    public const SSPI = 2048;              // 2^11 - Built against Windows SSPI
    public const CONV = 4096;              // 2^12 - Character conversions supported
    public const CURLDEBUG = 8192;         // 2^13 - Debug memory tracking supported
    public const TLSAUTH_SRP = 16384;      // 2^14 - TLS-SRP auth is supported
    public const NTLM_WB = 32768;          // 2^15 - NTLM delegation to winbind helper is supported
    public const HTTP2 = 65536;            // 2^16 - HTTP2 support built-in
    public const GSSAPI = 131072;          // 2^17 - Built against a GSS-API library
    public const KERBEROS5 = 262144;       // 2^18 - Kerberos V5 auth is supported
    public const UNIX_SOCKETS = 524288;    // 2^19 - Unix domain sockets support
    public const PSL = 1048576;            // 2^20 - Mozilla's Public Suffix List, used for cookie domain verification
    public const HTTPS_PROXY = 2097152;    // 2^21 - HTTPS-proxy support built-in
    public const MULTI_SSL = 4194304;      // 2^22 - Multiple SSL backends available
    public const BROTLI = 8388608;         // 2^23 - Brotli features are present

    private const FEATURES_LIST_KEY_FEATURE = 'feature';
    private const FEATURES_LIST_KEY_DESCRIPTION = 'description';
    private const FEATURES_LIST_KEY_CONSTANT = 'constant';
    private const FEATURES_LIST_KEY_PRESENT = 'present';

    private function __construct()
    {
    }

    /**
     * Checks if a cURL feature is enabled.
     *
     * @param int $feature cURL constant value representing feature
     */
    public static function isPresent(int $feature): bool
    {
        return (\curl_version()['features'] & $feature) === $feature;
    }

    /**
     * Returns list of all (documented) cURL features and their status.
     *
     * @return array[]
     */
    public static function listFeatures(): array
    {
        $features = \curl_version()['features'];

        return [
            static::IPV6 => [
                static::FEATURES_LIST_KEY_FEATURE => 'ipv6',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'IPv6-enabled',
                static::FEATURES_LIST_KEY_CONSTANT => static::IPV6,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::IPV6) === static::IPV6,
            ],
            static::KERBEROS4 => [
                static::FEATURES_LIST_KEY_FEATURE => 'kerberos4',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Kerberos V4 auth is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::KERBEROS4,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::KERBEROS4) === static::KERBEROS4,
            ],
            static::SSL => [
                static::FEATURES_LIST_KEY_FEATURE => 'ssl',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'SSL options are present',
                static::FEATURES_LIST_KEY_CONSTANT => static::SSL,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::SSL) === static::SSL,
            ],
            static::LIBZ => [
                static::FEATURES_LIST_KEY_FEATURE => 'libz',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'libz features are present',
                static::FEATURES_LIST_KEY_CONSTANT => static::LIBZ,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::LIBZ) === static::LIBZ,
            ],
            static::NTLM => [
                static::FEATURES_LIST_KEY_FEATURE => 'ntlm',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'NTLM auth is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::NTLM,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::NTLM) === static::NTLM,
            ],
            static::GSSNEGOTIATE => [
                static::FEATURES_LIST_KEY_FEATURE => 'gssnegotiate',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Negotiate auth is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::GSSNEGOTIATE,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::GSSNEGOTIATE) === static::GSSNEGOTIATE,
            ],
            static::DEBUG => [
                static::FEATURES_LIST_KEY_FEATURE => 'debug',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Built with debug capabilities',
                static::FEATURES_LIST_KEY_CONSTANT => static::DEBUG,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::DEBUG) === static::DEBUG,
            ],
            static::ASYNCHDNS => [
                static::FEATURES_LIST_KEY_FEATURE => 'asynchdns',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Asynchronous DNS resolves',
                static::FEATURES_LIST_KEY_CONSTANT => static::ASYNCHDNS,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::ASYNCHDNS) === static::ASYNCHDNS,
            ],
            static::SPNEGO => [
                static::FEATURES_LIST_KEY_FEATURE => 'spengo',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'SPNEGO auth is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::SPNEGO,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::SPNEGO) === static::SPNEGO,
            ],
            static::LARGEFILE => [
                static::FEATURES_LIST_KEY_FEATURE => 'largefile',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Supports files larger than 2GB',
                static::FEATURES_LIST_KEY_CONSTANT => static::LARGEFILE,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::LARGEFILE) === static::LARGEFILE,
            ],
            static::IDN => [
                static::FEATURES_LIST_KEY_FEATURE => 'idn',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Internationized Domain Names are supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::IDN,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::IDN) === static::IDN,
            ],
            static::SSPI => [
                static::FEATURES_LIST_KEY_FEATURE => 'sspi',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Built against Windows SSPI',
                static::FEATURES_LIST_KEY_CONSTANT => static::SSPI,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::SSPI) === static::SSPI,
            ],
            static::CONV => [
                static::FEATURES_LIST_KEY_FEATURE => 'conv',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Character conversions supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::CONV,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::CONV) === static::CONV,
            ],
            static::CURLDEBUG => [
                static::FEATURES_LIST_KEY_FEATURE => 'curldebug',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Debug memory tracking supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::CURLDEBUG,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::CURLDEBUG) === static::CURLDEBUG,
            ],
            static::TLSAUTH_SRP => [
                static::FEATURES_LIST_KEY_FEATURE => 'tslauth_srp',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'TLS-SRP auth is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::TLSAUTH_SRP,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::TLSAUTH_SRP) === static::TLSAUTH_SRP,
            ],
            static::NTLM_WB => [
                static::FEATURES_LIST_KEY_FEATURE => 'ntlm_wb',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'NTLM delegation to winbind helper is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::NTLM_WB,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::NTLM_WB) === static::NTLM_WB,
            ],
            static::HTTP2 => [
                static::FEATURES_LIST_KEY_FEATURE => 'http2',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'HTTP2 support built-in',
                static::FEATURES_LIST_KEY_CONSTANT => static::HTTP2,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::HTTP2) === static::HTTP2,
            ],
            static::GSSAPI => [
                static::FEATURES_LIST_KEY_FEATURE => 'gssapi',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Built against a GSS-API library',
                static::FEATURES_LIST_KEY_CONSTANT => static::GSSAPI,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::GSSAPI) === static::GSSAPI,
            ],
            static::KERBEROS5 => [
                static::FEATURES_LIST_KEY_FEATURE => 'kerberos5',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Kerberos V5 auth is supported',
                static::FEATURES_LIST_KEY_CONSTANT => static::KERBEROS5,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::KERBEROS5) === static::KERBEROS5,
            ],
            static::UNIX_SOCKETS => [
                static::FEATURES_LIST_KEY_FEATURE => 'unix_sockets',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Unix domain sockets support',
                static::FEATURES_LIST_KEY_CONSTANT => static::UNIX_SOCKETS,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::UNIX_SOCKETS) === static::UNIX_SOCKETS,
            ],
            static::PSL => [
                static::FEATURES_LIST_KEY_FEATURE => 'psl',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Mozilla\'s Public Suffix List, used for cookie domain verification',
                static::FEATURES_LIST_KEY_CONSTANT => static::PSL,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::PSL) === static::PSL,
            ],
            static::HTTPS_PROXY => [
                static::FEATURES_LIST_KEY_FEATURE => 'https_proxy',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'HTTPS-proxy support built-in',
                static::FEATURES_LIST_KEY_CONSTANT => static::HTTPS_PROXY,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::HTTPS_PROXY) === static::HTTPS_PROXY,
            ],
            static::MULTI_SSL => [
                static::FEATURES_LIST_KEY_FEATURE => 'multi_ssl',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Multiple SSL backends available',
                static::FEATURES_LIST_KEY_CONSTANT => static::MULTI_SSL,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::MULTI_SSL) === static::MULTI_SSL,
            ],
            static::BROTLI => [
                static::FEATURES_LIST_KEY_FEATURE => 'brotli',
                static::FEATURES_LIST_KEY_DESCRIPTION => 'Brotli features are present',
                static::FEATURES_LIST_KEY_CONSTANT => static::BROTLI,
                static::FEATURES_LIST_KEY_PRESENT => ($features & static::BROTLI) === static::BROTLI,
            ],
        ];
    }
}
