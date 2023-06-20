<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Mail\Inbox;

class InboxProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Inbox::class, function () {
            $configFile = new ConfigFile(Application::configPath('mail.php'));
            $config = $configFile->getSection('inbox');
            extract($config);
            $mailer = new Inbox(
                $driver,
                $host,
                intval($port),
                $username,
                $password
            );
            return $mailer;
        });
    }
}
