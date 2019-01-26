<?php

declare(strict_types=1);

namespace Tabellarium\Curl\CallableOption;

use Tabellarium\Curl\CurlHandle;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * Basic class usable for cURL option CURLOPT_READFUNCTION.
 *
 * @see http://php.net/manual/en/function.curl-setopt.php
 */
abstract class ReadCallable
{
    final public function __invoke($curlResource, $stream, int $length): string
    {
        return $this->invoke(
            new CurlHandle($curlResource),
                $stream,
                $length
        );
    }

    /**
     * Callback used by cURL reading data for send.
     *
     * Callback must return equal or smaller amount of data then specified with $length
     * Returning an empty string signals a EOF
     *
     * @param CurlHandleInterface cURL handle ressource wrapper
     * @param resource $stream A stream resource provided to cURL through the option CURLOPT_INFILE
     * @param int      $length The mamximum amout of data to be read
     *
     * @return string Read string
     */
    abstract protected function invoke(CurlHandleInterface $curlHandle, $stream, int $length): string;
}
