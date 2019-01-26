<?php

declare(strict_types=1);

namespace Tabellarium\Curl\Throwable\Exception;

use ErrorException as BaseErrorException;
use Tabellarium\Curl\Throwable\Throwable;

/**
 * Exception indicating PHP function execution error.
 */
class ErrorException extends BaseErrorException implements Throwable
{
}
