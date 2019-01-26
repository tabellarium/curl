<?php

declare(strict_types=1);

namespace Tabellarium\Curl;

class CurlHandleFactory implements CurlHandleFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function createCurlHandle($curl = null): CurlHandleInterface
    {
        return new CurlHandle($curl);
    }
}
