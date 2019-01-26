<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl\CallableOption;

use PHPUnit\Framework\TestCase;
use Tabellarium\Curl\CallableOption\ReadCallable;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * @internal
 * @coversNothing
 */
class ReadCallableTest extends TestCase
{
    /**
     * @test
     */
    public function invocation(): void
    {
        $curl = \curl_init();
        $resource = \tmpfile();
        $length = \random_int(10, 30);
        $return = \str_pad('string', $length);

        $class = new class() extends ReadCallable {
            public $curlHandle;
            public $resource;
            public $length;
            public $return;

            protected function invoke(CurlHandleInterface $curlResource, $stream, int $length): string
            {
                $this->curlHandle = $curlResource;
                $this->resource = $stream;
                $this->length = $length;

                return $this->return;
            }
        };

        $class->return = $return;

        static::assertSame(
            $return,
            $class($curl, $resource, $length),
            \sprintf(
                '%s::__invoke() failed to return result given to it by %s::invoke()',
                ReadCallable::class,
                ReadCallable::class
            )
        );

        static::assertSame(
            $curl,
            $class->curlHandle->detach(),
            \sprintf(
                '%s::__invoke() failed pass wrapped cURL resource handle to %s::invoke()',
                ReadCallable::class,
                ReadCallable::class
            )
        );

        static::assertSame(
            $resource,
            $class->resource,
            \sprintf(
                '%s::__invoke() failed pass stream to %s::invoke()',
                ReadCallable::class,
                ReadCallable::class
            )
        );

        static::assertSame(
            $length,
            $class->length,
            \sprintf(
                '%s::__invoke() failed pass length to %s::invoke()',
                ReadCallable::class,
                ReadCallable::class
            )
        );
    }
}
