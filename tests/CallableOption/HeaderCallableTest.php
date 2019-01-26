<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl\CallableOption;

use PHPUnit\Framework\TestCase;
use Tabellarium\Curl\CallableOption\HeaderCallable;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * @internal
 * @coversNothing
 */
class HeaderCallableTest extends TestCase
{
    /**
     * @test
     */
    public function invocation(): void
    {
        $curl = \curl_init();
        $string = \str_pad('some_string', \random_int(20, 50), '_');
        $return = \strlen($string) + \random_int(10, 30);

        $class = new class() extends HeaderCallable {
            public $curlHandle;
            public $string;
            public $return;

            protected function invoke(CurlHandleInterface $curlHandle, string $string): int
            {
                $this->curlHandle = $curlHandle;
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
                HeaderCallable::class,
                HeaderCallable::class
            )
        );

        static::assertSame(
            $curl,
            $class->curlHandle->detach(),
            \sprintf(
                '%s::__invoke() failed pass wrapped cURL resource handle to %s::invoke()',
                HeaderCallable::class,
                HeaderCallable::class
            )
        );

        static::assertSame(
            $string,
            $class->string,
            \sprintf(
                '%s::__invoke() failed pass string to %s::invoke()',
                HeaderCallable::class,
                HeaderCallable::class
            )
        );
    }
}
