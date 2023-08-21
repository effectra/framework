<?php

declare(strict_types=1);

namespace Effectra\Core\Server;

use Effectra\Core\Console\AppConsole;

class AppServer
{
    /**
     * The default host name for the server.
     */
    protected static string $HOST_NAME = '127.0.0.1';

    /**
     * Run the PHP development server on the specified port.
     *
     * @param int|string $port The port number to run the server on.
     * @return void
     */
    public static function run(int|string $port): void
    {
        // if (!static::isAvailablePort($port)) {
        //     echo "\033[0;31m This port '$port' it's not available now \033[0m";
        //     die;
        // }

        $command = sprintf("php -S %s:%s -t public/", static::$HOST_NAME, $port);
        AppConsole::print($command);
    }

    /**
     * Check if a port is available for binding.
     *
     * @param int $port The port number to check.
     * @return bool True if the port is available, false otherwise.
     */
    public static function isAvailablePort(int $port): bool
    {
        $socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    
        if (!$socket) {
            // Failed to create socket, port is likely not available
            return false;
        }
        
        // Set socket options to allow reusing the port
        @socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
        
        // Try binding the socket to the given port
        $result = @socket_bind($socket, '0.0.0.0', $port);
        
        // Clean up the socket
        @socket_close($socket);
        
        // If binding was successful, the port is available
        return $result;
    }
}
