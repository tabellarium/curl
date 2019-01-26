<?php

declare(strict_types=1);

namespace Tabellarium\Curl\CallableOption;

use Tabellarium\Curl\CurlHandle;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * Basic class usable for cURL option CURLOPT_WRITEFUNCTION.
 *
 * @see http://php.net/manual/en/function.curl-setopt.php
 */
abstract class WriteCallable
{
    final public function __invoke($curlResource, string $string): int
    {
        return $this->invoke(
            new CurlHandle($curlResource),
                $string
        );
    }

    /**
     * Callback used by cURL writing received data.
     *
     * Callback must return exact number of bytes written or the transfer will fail
     *
     * @param CurlHandleInterface cURL handle ressource wrapper
     * @param resource $string String to write
     *
     * @return int Number of bytes written
     */
    abstract protected function invoke(CurlHandleInterface $curlHandle, string $string): int;
}
