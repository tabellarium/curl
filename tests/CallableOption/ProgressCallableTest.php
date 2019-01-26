<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl\CallableOption;

use PHPUnit\Framework\TestCase;
use Tabellarium\Curl\CallableOption\ProgressCallable;
use Tabellarium\Curl\CurlHandleInterface;

/**
 * @internal
 * @coversNothing
 */
class ProgressCallableTest extends TestCase
{
    /**
     * @test
     */
    public function invocation(): void
    {
        $curl = \curl_init();

        $expectedDownload = \random_int(11, 15);
        $currentDownload = \random_int(1, 5);
        $expectedUpload = \random_int(16, 20);
        $currentUpload = \random_int(6, 10);
        $return = \random_int(21, 25);

        $class = new class() extends ProgressCallable {
            public $curlHandle;
            public $expectedDownload;
            public $downloadedSoFar;
            public $expectedUpload;
            public $uploadedSoFar;
            public $return;

            protected function invoke(CurlHandleInterface $curlHandle, int $expectedDownload, int $downloadedSoFar, int $expectedUpload, int $uploadedSoFar): int
            {
                $this->curlHandle = $curlHandle;
                $this->expectedDownload = $expectedDownload;
                $this->downloadedSoFar = $downloadedSoFar;
                $this->expectedUpload = $expectedUpload;
                $this->uploadedSoFar = $uploadedSoFar;

                return $this->return;
            }
        };

        $class->return = $return;

        static::assertSame(
            $return,
            $class($curl, $expectedDownload, $currentDownload, $expectedUpload, $currentUpload),
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
            $expectedDownload,
            $class->expectedDownload,
            \sprintf(
                '%s::__invoke() failed pass expected download size to %s::invoke()',
                HeaderCallable::class,
                HeaderCallable::class
            )
        );

        static::assertSame(
            $currentDownload,
            $class->downloadedSoFar,
            \sprintf(
                '%s::__invoke() failed pass current downloaded size so far size to %s::invoke()',
                HeaderCallable::class,
                HeaderCallable::class
            )
        );

        static::assertSame(
            $expectedUpload,
            $class->expectedUpload,
            \sprintf(
                '%s::__invoke() failed pass expected upload size to %s::invoke()',
                HeaderCallable::class,
                HeaderCallable::class
            )
        );

        static::assertSame(
            $currentUpload,
            $class->uploadedSoFar,
            \sprintf(
                '%s::__invoke() failed pass current uploaded size so far size to %s::invoke()',
                HeaderCallable::class,
                HeaderCallable::class
            )
        );
    }
}
