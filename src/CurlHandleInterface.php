<?php

declare(strict_types=1);

namespace Tabellarium\Curl;

use Tabellarium\Curl\Throwable\Exception\NoOpenHandleException;
use Tabellarium\Curl\Throwable\Exception\RuntimeException;

/**
 * This interface represents common opreations performable on a cURL handle
 * Error handling is performed in form of exceptions where possible.
 */
interface CurlHandleInterface
{
    /**
     * Copy a cURL handle along with all of its preferences.
     *
     * @see http://php.net/manual/function.curl-copy-handle.php
     */
    public function __clone();

    /**
     * Close a wrapped cURL session.
     *
     * @see http://php.net/manual/function.curl-close.php
     */
    public function close(): void;

    /**
     * Detaches underlying cURL session.
     *
     * @return null|resource Underlying handle or NULL if previously closed / detached
     */
    public function detach();

    /**
     * Set an option for a cURL transfer.
     *
     * @param int   $option the CURLOPT_XXX option to set
     * @param mixed $value  the value to be set on option
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     * @throws RuntimeException      on failure
     *
     * @see http://php.net/manual/function.curl-setopt.php
     */
    public function setOption(int $option, $value): void;

    /**
     * Set multiple options for a cURL transfer.
     *
     * @param array $options an array specifying which options to set and their values
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     * @throws RuntimeException      on failure
     *
     * @see http://php.net/manual/function.curl-setopt-array.php
     */
    public function setOptions(array $options): void;

    /**
     * Perform a cURL session.
     *
     * If the CURLOPT_RETURNTRANSFER option was set, this will return the result on success.
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     * @throws CurlException         for cURL related errors
     *
     * @return null|string Result if CURLOPT_RETURNTRANSFER was set, NULL otherwise
     *
     * @see http://php.net/manual/function.curl-exec.php
     */
    public function execute(): ?string;

    /**
     * Get information regarding a transfer.
     *
     * @param null|int $option the CURLINFO_XXX option to get
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     * @throws RuntimeException      on failure
     *
     * @return array|mixed Array of information or specific information if requested
     *
     * @see http://php.net/manual/function.curl-getinfo.php
     */
    public function getInformation(?int $option = null);

    /**
     * Reset all options of a libcurl session handle.
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     *
     * @see http://php.net/manual/function.curl-reset.php
     */
    public function reset(): void;

    /**
     * Pause and unpause a connection.
     *
     * @var int one of CURLPAUSE_* constants
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     * @throws CurlException         for cURL related errors
     *
     * @see http://php.net/manual/function.curl-pause.php
     */
    public function pause(int $bitmask): void;

    /**
     * URL encodes the given string according to RFC 3986.
     *
     * @param string $string String to be encoded
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     *
     * @return string Necoded string
     *
     * @see http://php.net/manual/function.curl-escape.php
     */
    public function escape(string $string): string;

    /**
     * Decodes the given URL encoded string.
     *
     * @param string $string String to be decoded
     *
     * @throws NoOpenHandleException if handle was previously closed or detached
     *
     * @return string Decoded string
     *
     * @see http://php.net/manual/function.curl-unescape.php
     */
    public function unescape(string $string): string;
}
