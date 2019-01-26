<?php

declare(strict_types=1);

namespace Tabellarium\Curl\Throwable\Exception;

/**
 * Exception thrown when underlying handle resource was previously closed or detached.
 */
class NoOpenHandleException extends RuntimeException
{
}
