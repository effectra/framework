<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Core\Database\AppDatabase;
use Effectra\Database\DB;

class DatabaseProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(DB::class, function () {

            $conn = AppDatabase::connect();

            $db = new DB($conn->connect());

            return $db;
        });
    }
}
