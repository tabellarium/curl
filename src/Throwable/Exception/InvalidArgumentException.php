<?php

declare(strict_types=1);

namespace Tabellarium\Curl\Throwable\Exception;

use InvalidArgumentException as BaseInvalidArgumentException;
use Tabellarium\Curl\Throwable\Throwable;

/**
 * Excpetion thrown when invalid argument was provided.
 */
class InvalidArgumentException extends BaseInvalidArgumentException implements Throwable
{
}
