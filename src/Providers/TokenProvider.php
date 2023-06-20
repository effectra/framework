<?php

declare(strict_types=1);

namespace Effectra\Core\Providers;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Core\Container\ServiceProvider;
use Effectra\Core\Contracts\ProviderInterface;
use Effectra\Core\Contracts\ServiceInterface;
use Effectra\Security\Token;

class TokenProvider  extends ServiceProvider implements ServiceInterface
{
    public function register(ProviderInterface $provider)
    {
        $provider->bind(Token::class, function () {

            $configFile = new ConfigFile(Application::configPath('auth.php'));
            $config = $configFile->getSection('token');

            $token = new Token();

            $token->config((object) $config);

            return $token;
        });
    }
}
