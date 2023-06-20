<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Database\Connection;
use Effectra\Database\DB;

class DatabaseProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(DB::class, function () {
            $configFile = new ConfigFile(Application::configPath('database.php'));
            $config = $configFile->read();
            extract($config['driver'][$config['default']]);

            $conn = new Connection(
                driver: $driver,
                host: $host,
                port: $port,
                username: $username,
                password: $password,
                database: $database,
            );

            $db = new DB($conn->connect());
            return $db;
        });
    }
}
