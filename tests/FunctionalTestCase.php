<?php

declare(strict_types=1);

namespace Tabellarium\Tests\Curl;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

abstract class FunctionalTestCase extends TestCase
{
    /**
     * @var Process
     */
    private static $server;

    public static function setUpBeforeClass(): void
    {
        if (!static::useCiServer()) {
            static::stopServer();
            self::fail('Failed staring test server.');
        }
    }

    public static function tearDownAfterClass(): void
    {
        static::stopServer();
    }

    public static function useCiServer()
    {
        return \filter_var(\getenv('CI_TEST_SERVER'), FILTER_VALIDATE_BOOLEAN);
    }

    protected static function getServerHost(): string
    {
        return $_ENV['TEST_HTTP_SERVER_HOST'];
    }

    protected static function getServerPort(): int
    {
        return static::useCiServer() ? 80 : \intval($_ENV['TEST_HTTP_SERVER_PORT']);
    }

    protected static function getServerUri(string $path): string
    {
        return \sprintf('http://%s:%d/%s', static::getServerHost(), static::getServerPort(), \ltrim($path, '/'));
    }

    protected static function stopServer(): void
    {
        if (self::$server instanceof Process) {
            self::$server->stop();
            self::$server = null;
        }
    }

    protected static function startServer(): void
    {
        self::stopServer();
        self::$server = new Process(
            ['php', '-S', \sprintf('%s:%d', static::getServerHost(), static::getServerPort())],
            \dirname(__DIR__).'/test_server'
        );
        self::$server->start();
        \usleep(100000); // sleep 0.1 s
    }
}
