<?php

declare(strict_types=1);

namespace Effectra\Core\Console;

use Effectra\Config\ConfigFile;
use Effectra\Core\Application as CoreApplication;
use Exception;
use Symfony\Component\Console\Application;

/**
 * Class AppConsole
 *
 * Handles the registration and execution of console commands.
 */
class AppConsole
{
    private array $commands = [];

    /**
     * Get the console configuration.
     *
     * @return array The console configuration.
     */
    private static function getConfig(): array
    {
        $file = CoreApplication::configPath('console.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->read();
        return $config;
    }

    /**
     * Get the registered console commands.
     *
     * @return array The registered console commands.
     */
    public function getCommands()
    {
        $config = static::getConfig();
        return $config['commands'] ?? [];
    }

    /**
     * Register the console commands.
     *
     * @throws Exception When no classes are added.
     * @return void
     */
    public function register()
    {
        $classes = $this->getCommands();

        if (count($classes) === 0) {
            throw new Exception("No classes added.");
        }

        foreach ($classes as $class) {
            $this->commands[] = $class;
        }
    }

    /**
     * Execute the console application.
     *
     * @return int The exit code of the console application.
     */
    public function execute()
    {
        $config = static::getConfig();

        $cli = new Application();

        $this->register();

        foreach ($this->commands as $class) {
            $cli->add(new $class);
        }

        if (!$config['disable']) {
            return $cli->run();
        }

        echo "\n Console App disabled !\n";
    }

    /**
     * Execute the given command and print the output with styling.
     *
     * @param string $command The command to execute.
     */
    public static function print(string $command): void
    {
        $output = [];
        exec($command, $output);

        // Display the output with styling
        foreach ($output as $line) {
            echo $line . PHP_EOL;
        }
    }
}
