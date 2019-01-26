<?php

declare(strict_types=1);

namespace Tabellarium\Curl;

interface CurlHandleFactoryInterface
{
    /**
     * Creates a new cURL handle wrapper.
     *
     * @param null|resource|string $curl NULL, cURL handle resource or URL
     *
     * @return CurlHandleInterface cURL handle wrapper
     */
    public function createCurlHandle($curl = null): CurlHandleInterface;
}
