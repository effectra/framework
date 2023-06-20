<?php

use Effectra\Core\Application;
use Effectra\Core\Client;
use Effectra\Core\Facades\View;
use Effectra\Core\Response;
use Effectra\Core\View as CoreView;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Effectra\Fs\Type\Json;
use Effectra\Mail\MailerService;
use Effectra\Router\Route;
use Effectra\Session\Session;
use Psr\Http\Message\ResponseInterface;

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, mixed $default = ""): mixed
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        } else return $default;
    }
}

if (!function_exists('app')) {

    function app(): Application
    {
        return Application::container()->get(Application::class);
    }
}

if (!function_exists('response')) {

    function response($statusCode = 200, array $headers = [], $body = '', $version = '1.1', $reasonPhrase = 'OK'): Response
    {
        return new Response(...func_get_args());
    }
}
if (!function_exists('database_path')) {

    function database_path(string $db = ''): string
    {
        return Application::databasePath('schema' . Path::ds() . $db);
    }
}

if (!function_exists('app_path')) {

    function app_path(string $path = ''): string
    {
        return Application::appPath($path);
    }
}

if (!function_exists('appSnakeName')) {
    function appSnakeName(): string
    {
        //replace this  $_ENV['APP_NAME']
        return strtolower(str_replace(' ', '_', 'effectra'));
    }
}

if (!function_exists('generateGuid')) {
    function generateGuid($customKey = '')
    {
        $hash = md5(uniqid($customKey, true));

        if (function_exists('com_create_guid') === true) {
            return trim(com_create_guid(), '{}');
        }

        return sprintf(
            '%08s-%04s-%04x-%04x-%12s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            mt_rand(0, 65535),
            mt_rand(16384, 20479),
            substr($hash, 12, 12)
        );
    }
}

if (!function_exists('router')) {
    function router()
    {
        return Application::container()->get(Route::class);
    }
}

if (!function_exists('session')) {
    function session(): Session
    {
        return Application::container()->get(Session::class);
    }
}

if (!function_exists('view')) {
    function view(string $view, $data = [])
    {
        $viewClass = Application::container()->get(CoreView::class);
        return $viewClass->render($view, $data);
    }
}

if (!function_exists('phpMailer')) {
    function phpMailer(): MailerService
    {
        return Application::container()->get(MailerService::class)->setupPHPMailer();
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url, int $statusCode = 302): ResponseInterface
    {
        return response()->redirect($url, $statusCode);
    }
}

if (!function_exists('fetch')) {
    function fetch($config = []): Client
    {
        return new Client($config);
    }
}

if (!function_exists('import')) {
    function import($file)
    {
        $path = Application::storagePath('import') . Path::ds() . $file;
        return File::getContent($path);
    }
}

if (!function_exists('importJson')) {
    function importJson($file, $associative = null)
    {
        $path = Application::storagePath('import') . Path::ds() . $file . '.json';
        return Json::decode($path, $associative);
    }
}

if (!function_exists('export')) {
    function export($fileName, $content, $lock = false): int|false
    {
        $path = Application::storagePath('export') . Path::ds() . $fileName;
        return File::put($path, $content, $lock);
    }
}

if (!function_exists('exportJson')) {
    function exportJson($fileName, $content, $lock = false): int|false
    {
        $path = Application::storagePath('export') . Path::ds() . $fileName . '.json';
        return File::put($path, $content, $lock);
    }
}

if (!function_exists('pre')) {
    function pre(mixed $value, array ...$values): void
    {
        echo '<style> body{background: #2c2c2c;color: #ccc;font-size: 15px} </style>';
        echo '<pre >';
        var_dump(...func_get_args());
        echo '<pre>';
        echo '<br>';
    }
}
