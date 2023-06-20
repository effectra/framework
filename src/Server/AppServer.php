<?php

declare(strict_types=1);

namespace Effectra\Core\Server;

class AppServer
{
    /**
     * The default host name for the server.
     */
    protected static string $HOST_NAME = '127.0.0.1';

    /**
     * Run the PHP development server on the specified port.
     *
     * @param int $port The port number to run the server on.
     * @return void
     */
    public static function run(int $port): void
    {
        $command = sprintf("php -S %s:%s -t public/", static::$HOST_NAME, $port);
        exec($command);
    }

    /**
     * Check if a port is available for binding.
     *
     * @param int $port The port number to check.
     * @return bool True if the port is available, false otherwise.
     */
    public static function isAvailablePort(int $port): bool
    {
        $socket = @stream_socket_server("tcp://" . static::$HOST_NAME . ":$port", $errno, $errstr);

        if ($socket === false) {
            return false;
        }

        fclose($socket);

        return true;
    }
}
