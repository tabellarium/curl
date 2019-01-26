<?php

declare(strict_types=1);

namespace Tabellarium\Curl\CallableOption;

use Tabellarium\Curl\CurlHandle;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * Basic class usable for cURL option CURLOPT_PROGRESSFUNCTION.
 *
 * @see http://php.net/manual/en/function.curl-setopt.php
 */
abstract class ProgressCallable
{
    final public function __invoke($curlResource, int $expectedDownload, int $downloadedSoFar, int $expectedUpload, int $uploadSoFar): int
    {
        return $this->invoke(
            new CurlHandle($curlResource),
                $expectedDownload,
                $downloadedSoFar,
                $expectedUpload,
                $uploadSoFar
            );
    }

    /**
     * Callback used by cURL for tracking progress headers.
     *
     * Returning non-zero value aborts the transfer
     *
     * @param CurlHandleInterface cURL handle ressource wrapper
     * @param int $expectedDownload Expected number of bytes to be downloaded
     * @param int $downloadedSoFar  Number of bytes downloaded so far
     * @param int $expectedUpload   Expected number of bytes to be uploaded
     * @param int $uploadedSoFar    Number of bytes uploaded so far
     *
     * @return int 0 to continue, other value to abort transfer
     */
    abstract protected function invoke(CurlHandleInterface $curlHandle, int $expectedDownload, int $downloadedSoFar, int $expectedUpload, int $uploadSoFar): int;
}
