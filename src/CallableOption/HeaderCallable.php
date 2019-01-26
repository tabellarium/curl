<?php

declare(strict_types=1);

namespace Tabellarium\Curl\CallableOption;

use Tabellarium\Curl\CurlHandle;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * Basic class usable for cURL option CURLOPT_HEADERFUNCTION.
 *
 * @see http://php.net/manual/en/function.curl-setopt.php
 */
abstract class HeaderCallable
{
    final public function __invoke($curlResource, string $header): int
    {
        return $this->invoke(
            new CurlHandle($curlResource),
            $header
        );
    }

    /**
     * Callback used by cURL for writting headers.
     *
     * Return value must return length of header, otherwise cURL will stop throwing an error
     *
     * @param CurlHandleInterface cURL handle ressource wrapper
     * @param string $header Header line
     *
     * @return int Number of bytes written
     */
    abstract protected function invoke(CurlHandleInterface $curlHandle, string $header): int;
}
