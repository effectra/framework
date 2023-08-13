<?php

use Effectra\Core\Application;
use Effectra\Core\Client;
use Effectra\Core\Localization;
use Effectra\Core\Request;
use Effectra\Core\Response;
use Effectra\Core\View;
use Effectra\Fs\File;
use Effectra\Fs\Path;
use Effectra\Fs\Type\Json;
use Effectra\Mail\MailerService;
use Effectra\Router\Route;
use Effectra\Session\Session;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

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

    function response(int $statusCode = 200, array $headers = [], $body = '',string $version = '1.1',string $reasonPhrase = 'OK'): Response
    {
        return new Response(...func_get_args());
    }
}

if (!function_exists('request')) {

    function request(
        string $method,
        UriInterface $uri,
        array $headers = [],
        $body = '',
        string $protocolVersion = '1.1',
        array $queryParams = [],
        $parsedBody = null,
        array $attributes = []
    ): Request {
        return new Request(...func_get_args());
    }
}

if (!function_exists('database_path')) {

    function database_path(string $db = ''): string
    {
        return Application::databasePath('schema' . Path::ds() . $db);
    }
}
if (!function_exists('public_path')) {

    function public_path(string $subfolder = ''): string
    {
        return Application::publicPath($subfolder);
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
        return strtolower(str_replace(' ', '_', $_ENV['APP_NAME']));
    }
}

if (!function_exists('generate_guid')) {
    function generate_guid($customKey = '')
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
        $viewClass = Application::container()->get(View::class);
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
        $path = Application::storagePath('imports') . Path::ds() . $file;
        return File::getContent($path);
    }
}

if (!function_exists('import_json')) {
    function import_json($file, $associative = null)
    {
        $path = Application::storagePath('imports') . Path::ds() . $file . '.json';
        return Json::decode($path, $associative);
    }
}

if (!function_exists('export')) {
    function export($fileName, $content, $lock = false): int|false
    {
        $path = Application::storagePath('exports') . Path::ds() . $fileName;
        return File::put($path, $content, $lock);
    }
}

if (!function_exists('export_json')) {
    function export_json($fileName, $content, $lock = false): int|false
    {
        $path = Application::storagePath('exports') . Path::ds() . $fileName . '.json';
        return File::put($path, $content, $lock);
    }
}


if (!function_exists('now')) {
    function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}


if (!function_exists('translate')) {
    function translate(string $key, ?string $lang = null): string
    {
        if (!$lang) {
            $lang = Application::getLang();
        }
        $localization = new Localization($lang);
        return $localization->translate($key);
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
