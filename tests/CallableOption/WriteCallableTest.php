<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl\CallableOption;

use PHPUnit\Framework\TestCase;
use Tabellarium\Curl\CallableOption\WriteCallable;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * @internal
 * @coversNothing
 */
class WriteCallableTest extends TestCase
{
    /**
     * @test
     */
    public function invocation(): void
    {
        $curl = \curl_init();
        $resource = \tmpfile();
        $string = \str_pad('string', \random_int(10, 30));
        $return = \random_int(10, 15);

        $class = new class() extends WriteCallable {
            public $curlHandle;
            public $string;
            public $return;

            protected function invoke(CurlHandleInterface $curlResource, string $string): int
            {
                $this->curlHandle = $curlResource;
                $this->string = $string;

                return $this->return;
            }
        };

        $class->return = $return;

        static::assertSame(
            $return,
            $class($curl, $string),
            \sprintf(
                '%s::__invoke() failed to return result given to it by %s::invoke()',
                WriteCallable::class,
                WriteCallable::class
            )
        );

        static::assertSame(
            $curl,
            $class->curlHandle->detach(),
            \sprintf(
                '%s::__invoke() failed pass wrapped cURL resource handle to %s::invoke()',
                WriteCallable::class,
                WriteCallable::class
            )
        );

        static::assertSame(
            $string,
            $class->string,
            \sprintf(
                '%s::__invoke() failed pass string to %s::invoke()',
                WriteCallable::class,
                WriteCallable::class
            )
        );
    }
}
