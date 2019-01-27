<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl;

use Tabellarium\Curl\CurlHandle;
use Tabellarium\Curl\Throwable\Exception\CurlException;
use Tabellarium\Curl\Throwable\Exception\RuntimeException;

/**
 * @internal
 * @coversNothing
 */
class CurlHandleTest extends FunctionalTestCase
{
    /**
     * @test
     */
    public function constructorWithoutArgumentsCreatesNewWrappedCurlHandle(): void
    {
        $curl = new CurlHandle();
        $resource = $curl->detach();

        static::assertIsResource(
            $resource,
            \sprintf(
                'Failed verifiyng that %s constructor created new cURL handle resource when no argument was provided; no resource created.',
                \get_class($curl)
            )
        );

        static::assertSame(
            'curl',
            \get_resource_type($resource),
            \sprintf(
                'Failed verifiyng that %s constructor created new cURL handle resource when no argument was provided; resource was not curl.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     */
    public function constructorWithStringArgumentCreatesNewWrappedCurlHandleWithPresetUrl(): void
    {
        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);

        $curl = new CurlHandle(static::getServerUri($uri));
        $resource = $curl->detach();

        static::assertIsResource(
            $resource,
            \sprintf(
                'Failed verifiyng that %s constructor created new cURL handle resource when string argument was provided; no resource created.',
                \get_class($curl)
            )
        );

        static::assertSame(
            'curl',
            \get_resource_type($resource),
            \sprintf(
                'Failed verifiyng that %s constructor created new cURL handle resource when string argument was provided; resource was not curl.',
                \get_class($curl)
            )
        );

        \curl_setopt($resource, CURLOPT_RETURNTRANSFER, true);

        static::assertStringContainsString(
            $uri,
            \curl_exec($resource),
            \sprintf(
                'Failed verifiyng that %s constructor created new cURL handle resource when string argument was provided using it as a url for cURL handle.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     */
    public function constructorWithCurlResourceArgumentWrapsGivenResource(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        static::assertSame(
            $resource,
            $curl->detach(),
            \sprintf(
                'Failed verifiyng that %s constructor wrapped provided cURL handle resource.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function destructorWillNotCloseCurlHandleGivenToConstructor(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        $curl->__destruct();

        static::assertTrue(
            \is_resource($resource) && 'curl' === \get_resource_type($resource),
            \sprintf(
                'Failed verifiyng that %s destructor did not close cURL resource provided to constructor.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithoutArgumentsCreatesNewWrappedCurlHandle
     */
    public function detachDetachesUnderlyingHandle(): void
    {
        $curl = new CurlHandle();
        $curl->detach();

        static::assertNull(
            $curl->detach(),
            \sprintf(
                'Failed verifiyng that %s::detach() actually detached the resource; subsequent call failed to return NULL.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function closeClosesUnderlyingHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        $curl->close();

        static::assertFalse(
            \is_resource($resource) && 'curl' === \get_resource_type($resource),
            \sprintf(
                'Failed verifiyng that %s::close() closed the cURL resource.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @depends closeClosesUnderlyingHandle
     */
    public function closeDoesNotThrowExceptionOnAlreadyClosedResource(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        $curl->close();
        $curl->close();
        static::assertTrue(true);
    }

    /**
     * @test
     * @depends constructorWithoutArgumentsCreatesNewWrappedCurlHandle
     */
    public function setOptionAndExecuteReturningString(): void
    {
        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);

        $curl = new CurlHandle();
        $curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $curl->setOption(CURLOPT_URL, static::getServerUri($uri));

        static::assertStringContainsString(
            $uri,
            $curl->execute(),
            \sprintf(
                'Failed verifiyng that %s methods ::setOption() and ::execute() are able to set cURL options and return string value when return trasnfer is set.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithoutArgumentsCreatesNewWrappedCurlHandle
     * @depends setOptionAndExecuteReturningString
     */
    public function setOptionAndExecuteReturningNull(): void
    {
        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);

        $curl = new CurlHandle();
        $curl->setOption(CURLOPT_RETURNTRANSFER, false);
        $curl->setOption(CURLOPT_URL, static::getServerUri($uri));

        \ob_start();

        try {
            $result = $curl->execute();
        } finally {
            \ob_end_clean();
        }

        static::assertNull(
            $result,
            \sprintf(
                'Failed verifiyng that %s methods ::setOption() and ::execute() are able to set cURL options and return NULL value when return transfer is not set.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\NoOpenHandleException
     */
    public function setOptionThrowsNoOpenHandleExceptionOnClosedHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        $curl->setOption(CURLOPT_URL, 'localhost');
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\RuntimeException
     */
    public function setOptionThrowsRuntimeExceptionOnFailure(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        $curl->setOption(CURLOPT_URL, []);
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\NoOpenHandleException
     */
    public function executeThrowsNoOpenHandleExceptionOnClosedHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        $curl->execute();
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function executeThrowsCurlExceptionOnCurlError(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        try {
            $curl->execute();
        } catch (CurlException $e) {
            $this->expectException(CurlException::class);
            $this->expectExceptionMessage(\curl_error($resource));
            $this->expectExceptionCode(\curl_errno($resource));

            throw $e;
        }
    }

    /**
     * @test
     * @depends constructorWithoutArgumentsCreatesNewWrappedCurlHandle
     * @depends setOptionAndExecuteReturningString
     */
    public function setOptionsSetsOptions(): void
    {
        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);

        $curl = new CurlHandle();
        $curl->setOptions(
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri($uri),
            ]
        );

        static::assertStringContainsString(
            $uri,
            $curl->execute(),
            \sprintf(
                'Failed verifiyng that %s methods ::setOption() and ::execute() are able to set cURL options and return string value when return trasnfer is set.',
                \get_class($curl)
            )
        );

        static::assertStringContainsString(
            $uri,
            $curl->execute(),
            \sprintf(
                'Failed verifiyng that %s methods ::setOptions() and ::execute() are able to set cURL options and return string value when return trasnfer is set.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\NoOpenHandleException
     */
    public function setOptionsThrowsNoOpenHandleExceptionOnClosedHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        $curl->setOptions([]);
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\RuntimeException
     */
    public function setOptionsThrowsRuntimeExceptionOnFailure(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        $curl->setOptions([CURLOPT_URL, []]);
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @depends setOptionsThrowsRuntimeExceptionOnFailure
     */
    public function setOptionSetsOptionsUpToFirstFailedSet(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);

        try {
            $curl->setOptions(
                [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_URL => static::getServerUri($uri),
                    PHP_INT_MIN => [],
                    CURLOPT_CUSTOMREQUEST => 'POST',
                ]
            );
        } catch (RuntimeException $e) {
        }

        $curl->execute();
        static::assertStringContainsString(
            'GET '.$uri,
            $curl->execute(),
            \sprintf(
                'Failed verifiyng that %s methods ::setOptions() has set all options up to first failure.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function getInformationReturnsInformationArrayIfNoArgumentSpecified(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri('/request.php'),
            ]
        );

        $curl->execute();

        static::assertSame(
            \curl_getinfo($resource),
            $curl->getInformation(),
            \sprintf(
                'Faield verifiying that %s method ::getInformation() will return expected informationa array when no argument was specified.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function getInformationReturnsRequestedInformationWhenArgumentSpecified(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri('/request.php'),
            ]
        );

        $curl->execute();

        foreach ([CURLINFO_LOCAL_IP, CURLINFO_EFFECTIVE_URL] as $option) {
            static::assertSame(
                \curl_getinfo($resource, $option),
                $curl->getInformation($option),
                \sprintf(
                    'Faield verifiying that %s method ::getInformation() will return expected informationa when argument was specified.',
                    \get_class($curl)
                )
            );
        }
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\NoOpenHandleException
     */
    public function getInformationThrowsNoOpenHandleExceptionOnClosedHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        $curl->getInformation();
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\RuntimeException
     */
    public function getInformationThrowsRuntimeExceptionOnFailure(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        $curl->getInformation(PHP_INT_MIN);
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function resetResetsCurlOptions(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);

        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri('/request.php'),
                CURLOPT_CUSTOMREQUEST => 'POST',
            ]
        );

        $curl->reset();

        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri('/request.php'),
            ]
        );

        static::assertStringStartsWith(
            'GET ',
            \curl_exec($resource),
            \sprintf(
                'Failed verifying that %s ::reset() option has reset cURL handle options.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\NoOpenHandleException
     */
    public function resetThrowsNoOpenHandleExceptionOnClosedHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        $curl->reset();
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @depends destructorWillNotCloseCurlHandleGivenToConstructor
     * @runInSeparateProcess
     */
    public function pausePausesCurlConnection(): void
    {
        \set_time_limit(5);
        $resource = \curl_init();
        $mh = \curl_multi_init();
        \curl_multi_add_handle($mh, $resource);

        $curl = new CurlHandle($resource);

        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri('/request.php'),
                CURLOPT_HEADERFUNCTION => function ($resource, $header) {
                    $curl = new CurlHandle($resource);
                    if (0 === \strpos($header, 'HTTP/')) {
                        $curl->pause(CURLPAUSE_ALL);
                    }

                    return \strlen($header);
                },
            ]
        );

        do {
            $mrc = \curl_multi_exec($mh, $active);
        } while (CURLM_CALL_MULTI_PERFORM == $mrc);

        while ($active && CURLM_OK == $mrc) {
            if (-1 != \curl_multi_select($mh)) {
                do {
                    $mrc = \curl_multi_exec($mh, $active);
                } while (CURLM_CALL_MULTI_PERFORM == $mrc);
            }
        }

        static::assertSame(
            '',
            \curl_multi_getcontent($resource),
            \sprintf(
                'Failed verifiying that %s ::pause() managed to pause cURL connection',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @depends destructorWillNotCloseCurlHandleGivenToConstructor
     * @expectedException \Tabellarium\Curl\Throwable\Exception\CurlException
     * @runInSeparateProcess
     */
    public function pauseThrowsCurlException(): void
    {
        if (\version_compare(\curl_version()['version'], '7.60', '<')) {
            static::markTestSkipped('Unpausing not working on cURL prior 7.60');
        }

        $resource = \curl_init();
        $mh = \curl_multi_init();
        \curl_multi_add_handle($mh, $resource);
        \set_time_limit(5);

        $curl = new CurlHandle($resource);

        $pauseTime = 0.5;

        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri('/request.php'),
                CURLOPT_HEADERFUNCTION => function ($resource, $header) {
                    $curl = new CurlHandle($resource);
                    if (0 === \strpos($header, 'HTTP/')) {
                        $curl->pause(CURLPAUSE_ALL);

                        return \strlen($header);
                    }

                    return 0;
                },
            ]
        );

        $maxEnd = \time() + $pauseTime;

        do {
            $mrc = \curl_multi_exec($mh, $active);
        } while (CURLM_CALL_MULTI_PERFORM == $mrc && $maxEnd > \time());

        while ($active && CURLM_OK == $mrc && $maxEnd > \time()) {
            if (-1 != \curl_multi_select($mh)) {
                do {
                    $mrc = \curl_multi_exec($mh, $active);
                } while (CURLM_CALL_MULTI_PERFORM == $mrc && $maxEnd > \time());
            }
        }

        try {
            $curl->pause(CURLPAUSE_CONT);
        } catch (CurlException $e) {
            $this->expectException(CurlException::class);
            $this->expectExceptionMessage(\curl_error($resource));
            $this->expectExceptionCode(\curl_errno($resource));

            throw $e;
        }
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     * @expectedException \Tabellarium\Curl\Throwable\Exception\NoOpenHandleException
     */
    public function pauseThrowsNoOpenHandleExceptionOnClosedHandle(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        $curl->pause(CURLPAUSE_ALL);
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function cloneCopysHandle(): void
    {
        $uri = '/request.php?param='.\random_int(0, PHP_INT_MAX);
        $resource = \curl_init();
        \curl_setopt_array(
            $resource,
            [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_URL => static::getServerUri($uri),
            ]
        );

        $curl = new CurlHandle($resource);
        $curlCopy = clone $curl;
        $resourceCopy = $curlCopy->detach();

        \curl_setopt($resourceCopy, CURLOPT_CUSTOMREQUEST, 'POST');

        static::assertStringStartsWith(
            'GET '.$uri,
            \curl_exec($resource),
            \sprintf(
                'Failed verifying that %s ::__clone() did not return wrapper containing original resource.',
                \get_class($curl)
            )
        );

        static::assertStringStartsWith(
            'POST '.$uri,
            \curl_exec($resourceCopy),
            \sprintf(
                'Failed verifying that %s ::__clone() returned wrapper containing copy of original resource.',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithCurlResourceArgumentWrapsGivenResource
     */
    public function cloneDoesNotThrowExceptionOnNonOpenResource(): void
    {
        $resource = \curl_init();
        $curl = new CurlHandle($resource);
        \curl_close($resource);

        clone $curl;

        static::assertTrue(true);
    }

    /**
     * @test
     * @depends constructorWithoutArgumentsCreatesNewWrappedCurlHandle
     */
    public function escapeEncodesString(): void
    {
        $curl = new CurlHandle();
        static::assertSame(
            'Some%20%5Bstring%5D%20to%20encode',
            $curl->escape('Some [string] to encode'),
            \sprintf(
                'Failed to verify that %s ::encode() method encodes the string according to RFC 3986',
                \get_class($curl)
            )
        );
    }

    /**
     * @test
     * @depends constructorWithoutArgumentsCreatesNewWrappedCurlHandle
     */
    public function unescapeDecodesString(): void
    {
        $curl = new CurlHandle();
        static::assertSame(
            'Some [string] to encode',
            $curl->unescape('Some%20%5Bstring%5D%20to%20encode'),
            \sprintf(
                'Failed to verify that %s ::encode() method decodes the string according to RFC 3986',
                \get_class($curl)
            )
        );
    }
}
