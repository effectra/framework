<?php

declare(strict_types=1);

namespace Effectra\Core\Mail;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application;
use Effectra\Mail\Components\Address;

class AppMail
{
    /**
     * Get the database configuration.
     *
     * @return array The database configuration.
     */
    public static function getConfig()
    {
        $file = Application::configPath('mail.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();

        return $config;
    }

    public static function senderConfig()
    {
        return static::getConfig()['sender'];
    }

    public static function inboxConfig()
    {
        return static::getConfig()['inbox'];
    }

    public static function fromAddress(): Address
    {
        $sender = static::senderConfig();
        return new Address($sender['from'],$_ENV['APP_NAME'] ?? '');
    }
}
