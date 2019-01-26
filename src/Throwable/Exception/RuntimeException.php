<?php

declare(strict_types=1);

namespace Tabellarium\Curl\Throwable\Exception;

use RuntimeException as BaseRuntimeException;
use Tabellarium\Curl\Throwable\Throwable;

/**
 * Runtime exception.
 */
class RuntimeException extends BaseRuntimeException implements Throwable
{
}
