<?php

declare(strict_types=1);

namespace Tabellarium\Curl;

use Tabellarium\Curl\Throwable\Exception\CurlException;
use Tabellarium\Curl\Throwable\Exception\ErrorException;
use Tabellarium\Curl\Throwable\Exception\InvalidArgumentException;
use Tabellarium\Curl\Throwable\Exception\NoOpenHandleException;
use Tabellarium\Curl\Throwable\Exception\RuntimeException;
use Throwable;

class CurlHandle implements CurlHandleInterface
{
    /**
     * @var resource cURL handle resource
     */
    private $handle;

    /**
     * @var bool Should cURL handle be closed on object destruction
     */
    private $closeOnDestruct = false;

    public function __construct($handle = null)
    {
        if (\is_string($handle)) {
            $this->handle = \curl_init($handle);
            $this->closeOnDestruct = true;
        } elseif (null === $handle) {
            $this->handle = \curl_init();
            $this->closeOnDestruct = true;
        } elseif (\is_resource($handle) && 'curl' === \get_resource_type($handle)) {
            $this->handle = $handle;
            $this->closeOnDestruct = false;
        } else {
            throw new InvalidArgumentException(
                \sprintf(
                    '%s constructor expects $handle argument to be NULL, string or a cURL resource; got : %s',
                    \get_class($this),
                    \is_object($handle)
                        ? \get_class($handle)
                        : (\is_resource($handle) ? \sprintf('%s resource', \get_resource_type($handle)) : \gettype($handle))
                )
            );
        }
    }

    public function __destruct()
    {
        if ($this->closeOnDestruct) {
            $this->close();
        }
    }

    public function __clone()
    {
        if (\is_resource($this->handle)) {
            $this->handle = \curl_copy_handle($this->handle);
            $this->closeOnDestruct = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close(): void
    {
        if (\is_resource($this->handle)) {
            \curl_close($this->handle);
            $this->handle = null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function detach()
    {
        list($return, $this->handle) = [$this->handle, null];

        return null !== $return && \is_resource($return) ? $return : null;
    }

    /**
     * {@inheritdoc}
     */
    public function setOption(int $option, $value): void
    {
        $handle = $this->getOpenHandle();

        \set_error_handler(function (int $severity, string $message, string $file, int $line): void {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            if (false === \curl_setopt($handle, $option, $value)) {
                throw new RuntimeException('Unkown error occured executing curl_setopt()');
            }
        } catch (Throwable $t) {
            throw new RuntimeException(
                'Error occured while setting cURL session option',
                0,
                $t
            );
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options): void
    {
        $handle = $this->getOpenHandle();

        \set_error_handler(function (int $severity, string $message, string $file, int $line): void {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            // While PHP documentation states curl_setopt_array will stop after first failure
            // this is not always the case. To mimic expected behaviour, we are looping through options
            // and setting this one by one, throwing exception on first error
            foreach ($options as $option => $value) {
                if (false === \curl_setopt($handle, $option, $value)) {
                    throw new RuntimeException('Unkown error occured executing curl_setopt()');
                }
            }
        } catch (Throwable $t) {
            throw new RuntimeException(
                'Error occured while setting cURL session options',
                0,
                $t
            );
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): ?string
    {
        $handle = $this->getOpenHandle();

        \set_error_handler(function (int $severity, string $message, string $file, int $line): void {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            if (false === $result = \curl_exec($handle)) {
                throw new CurlException(
                    \curl_error($handle),
                    \curl_errno($handle)
                );
            }

            return true === $result ? null : $result;
        } catch (CurlException $e) {
            throw $e;
        } catch (Throwable $t) {
            throw new RuntimeException(
                'Error occured while executing cURL session',
                0,
                $t
            );
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getInformation(?int $option = null)
    {
        $handle = $this->getOpenHandle();

        \set_error_handler(function (int $severity, string $message, string $file, int $line): void {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            if (false === $return = (null === $option ? \curl_getinfo($handle) : \curl_getinfo($handle, $option))) {
                throw new RuntimeException('Error getting cURL information');
            }

            return $return;
        } catch (Throwable $t) {
            throw new RuntimeException(
                'Error occured while getting cURL session information',
                0,
                $t
            );
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function reset(): void
    {
        \curl_reset($this->getOpenHandle());
    }

    /**
     * {@inheritdoc}
     */
    public function pause(int $bitmask): void
    {
        $handle = $this->getOpenHandle();

        \set_error_handler(function (int $severity, string $message, string $file, int $line): void {
            throw new ErrorException($message, 0, $severity, $file, $line);
        });

        try {
            if (CURLE_OK !== $result = \curl_pause($handle, $bitmask)) {
                throw new CurlException(
                    \curl_error($handle),
                    \curl_errno($handle)
                );
            }
        } catch (CurlException $e) {
            throw $e;
        } catch (Throwable $t) {
            throw new RuntimeException(
                'Error occured while pausing/unpausing cURL session',
                0,
                $t
            );
        } finally {
            \restore_error_handler();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function escape(string $string): string
    {
        return \curl_escape($this->getOpenHandle(), $string);
    }

    /**
     * {@inheritdoc}
     */
    public function unescape(string $string): string
    {
        return \curl_unescape($this->getOpenHandle(), $string);
    }

    /**
     * Returns an open handle or throws an exception.
     *
     * @throws NoOpenHandleException for closed or detached cURL handle
     *
     * @return resource Open cURL handle
     */
    private function getOpenHandle()
    {
        if (\is_resource($this->handle)) {
            return $this->handle;
        }

        throw new NoOpenHandleException('cURL handle has been closed or detached');
    }
}
