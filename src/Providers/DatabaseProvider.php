<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\Database\AppDatabase;
use Effectra\Database\DB;
use Psr\EventDispatcher\EventDispatcherInterface;

class DatabaseProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(DB::class, function () {

            $conn = AppDatabase::connect();
            $db = new DB();
            DB::createConnection($conn);
            DB::setEventDispatcher(Application::container()->get(EventDispatcherInterface::class));
            return $db;
        });
    }
}
