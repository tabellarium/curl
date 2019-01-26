<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl;

use Tabellarium\Curl\CurlHandleFactory;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * @internal
 * @coversNothing
 */
class CurlHandleFactoryTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function createCurlHandleWithoutArgumentReturnsCurlHandleWrapper(): void
    {
        $factory = new CurlHandleFactory();

        static::assertInstanceOf(
            CurlHandleInterface::class,
            $factory->createCurlHandle(),
            \sprintf(
                'Failed to verify that %s ::createCurlHadle() method returned cURL handle wrapper.',
                \get_class($factory),
                'createCurlHandle'
            )
        );
    }

    /**
     * @test
     */
    public function createCurlHandleWithCurlResourceArgumentReturnsCurlHandleWrapperWithWrappedResource(): void
    {
        $resource = \curl_init();
        $factory = new CurlHandleFactory();
        $curl = $factory->createCurlHandle($resource);

        static::assertInstanceOf(
            CurlHandleInterface::class,
            $curl,
            \sprintf(
                'Failed to verify that %s ::createCurlHandleFromResource() method returned cURL handle wrapper.',
                \get_class($factory),
                'createCurlHandle'
            )
        );

        static::assertSame(
            $resource,
            $curl->detach(),
            \sprintf(
                'Failed to verify that %s ::createCurlHandleFromResource() method returned cURL handle wrapper wrapping provided resource.',
                \get_class($factory),
                'createCurlHandle'
            )
        );
    }

    /**
     * @test
     * @expectedException \Tabellarium\Curl\Throwable\Exception\InvalidArgumentException
     */
    public function createCurlHandleThrowsInvalidArgumentExceptionForNonValidResource(): void
    {
        $factory = new CurlHandleFactory();
        $curl = $factory->createCurlHandle(\tmpfile());
    }

    /**
     * @test
     */
    public function createCurlHandleWithStringArgumentReturnsCurlHandleWrapperForGivenUrl(): void
    {
        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);

        $factory = new CurlHandleFactory();
        $curl = $factory->createCurlHandle(static::getServerUri($uri));
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);

        static::assertStringContainsString(
            $uri,
            $curl->execute(),
            \sprintf(
                'Failed to verify that %s ::createCurlHandleFromUrlString() method returned cURL handle wrapper wrapping handle pointing to specified URL.',
                \get_class($curl)
            )
        );
    }
}
