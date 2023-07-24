<?php

declare(strict_types=1);

namespace Effectra\Core\Test;

use Effectra\Core\Console\AppConsole;

/**
 * Class AppTest
 *
 * A utility class for running PHPUnit tests from the command line.
 */
class AppTest
{
    /**
     * Run PHPUnit tests using the specified command.
     *
     * @param string $command The command to run PHPUnit tests. Defaults to an empty string to run all tests.
     */
    public static function run(string $command = ''): void
    {
        AppConsole::print('php vendor/bin/phpunit ' . $command);
    }
}
