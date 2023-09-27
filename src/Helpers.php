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
     * @param string $key The name of the environment variable.
     * @param mixed $default The default value to return if the variable is not set.
     *
     * @return mixed The value of the environment variable or the default value if not set.
     */
    function env(string $key, mixed $default = ""): mixed
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        } else return $default;
    }
}

if (!function_exists('app')) {
    /**
     * Get the Application instance.
     *
     * @return Application The Application instance.
     */
    function app(): Application
    {
        return Application::container()->get(Application::class);
    }
}

if (!function_exists('app_key')) {
    /**
     * Get the application key.
     *
     * @return string The application key.
     */
    function app_key(): string
    {
        return env('APP_KEY');
    }
}

if (!function_exists('response')) {
    /**
     * Create a new HTTP response.
     *
     * @param int $statusCode The HTTP status code.
     * @param array $headers The HTTP headers.
     * @param mixed $body The response body.
     * @param string $version The HTTP protocol version.
     * @param string $reasonPhrase The HTTP reason phrase.
     *
     * @return Response The created HTTP response.
     */
    function response(int $statusCode = 200, array $headers = [], $body = '', string $version = '1.1', string $reasonPhrase = 'OK'): Response
    {
        return new Response(...func_get_args());
    }
}

if (!function_exists('request')) {
    /**
     * Create a new HTTP request.
     *
     * @param string $method The HTTP request method.
     * @param UriInterface $uri The request URI.
     * @param array $headers The request headers.
     * @param mixed $body The request body.
     * @param string $protocolVersion The HTTP protocol version.
     * @param array $queryParams The query parameters.
     * @param mixed $parsedBody The parsed request body.
     * @param array $attributes The request attributes.
     *
     * @return Request The created HTTP request.
     */
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
    /**
     * Get the path to the database directory.
     *
     * @param string $db The name of the database directory.
     *
     * @return string The path to the database directory.
     */
    function database_path(string $db = ''): string
    {
        return Application::databasePath('schema' . Path::ds() . $db);
    }
}
if (!function_exists('public_path')) {
    /**
     * Get the path to the public directory or a subfolder within it.
     *
     * @param string $subfolder The subfolder within the public directory.
     *
     * @return string The path to the public directory or subfolder.
     */
    function public_path(string $subfolder = ''): string
    {
        return Application::publicPath($subfolder);
    }
}
if (!function_exists('app_path')) {
    /**
     * Get the path to the application directory or a subfolder within it.
     *
     * @param string $path The subpath within the application directory.
     *
     * @return string The path to the application directory or subpath.
     */
    function app_path(string $path = ''): string
    {
        return Application::appPath($path);
    }
}

if (!function_exists('appSnakeName')) {
    /**
     * Get the snake case name of the application.
     *
     * @return string The snake case name of the application.
     */
    function appSnakeName(): string
    {
        return strtolower(str_replace(' ', '_', $_ENV['APP_NAME']));
    }
}

if (!function_exists('generate_guid')) {
    /**
     * Generate a globally unique identifier (GUID).
     *
     * @param string $customKey An optional custom key to incorporate into the GUID generation.
     *
     * @return string The generated GUID.
     */
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
    /**
     * Get the router instance.
     *
     * @return Route The router instance.
     */
    function router()
    {
        return Application::container()->get(Route::class);
    }
}

if (!function_exists('session')) {
    /**
     * Get the session instance.
     *
     * @return Session The session instance.
     */
    function session(): Session
    {
        return Application::container()->get(Session::class);
    }
}

if (!function_exists('view')) {
    /**
     * Render a view with optional data.
     *
     * @param string $view The name of the view.
     * @param array $data An optional array of data to pass to the view.
     *
     * @return mixed The rendered view.
     */
    function view(string $view, $data = [])
    {
        $viewClass = Application::container()->get(View::class);
        return $viewClass->render($view, $data);
    }
}

if (!function_exists('phpMailer')) {
    /**
     * Import content from a file in the "imports" directory.
     *
     * @param string $file The name of the file to import.
     *
     * @return mixed The imported content.
     */
    function phpMailer(): MailerService
    {
        return Application::container()->get(MailerService::class)->setupPHPMailer();
    }
}

if (!function_exists('redirect')) {
    /**
     * @param string $url — The URL to redirect to.
     * @param int $statusCode The HTTP status code for the redirect response. Default is 302 (Found).
     * @return ResponseInterface
     */
    function redirect(string $url, int $statusCode = 302): ResponseInterface
    {
        return response()->redirect($url, $statusCode);
    }
}

if (!function_exists('fetch')) {
    /**
     * @param array $config  Client configuration settings.
     * @return Client Extends the GuzzleHttp\Client class to provide additional functionalities and customization options.
     */
    function fetch($config = []): Client
    {
        return new Client($config);
    }
}

if (!function_exists('import')) {
    /**
     * Import content from a file in the "imports" directory.
     *
     * @param string $file The name of the file to import.
     *
     * @return mixed The imported content.
     */
    function import($file)
    {
        $path = Application::storagePath('imports') . Path::ds() . $file;
        return File::getContent($path);
    }
}

if (!function_exists('import_json')) {
    /**
     * Import content from a JSON file in the "imports" directory.
     *
     * @param string $file The name of the JSON file to import.
     * @param bool|null $associative Whether to decode the JSON as an associative array.
     *
     * @return mixed The decoded JSON content.
     */
    function import_json($file, $associative = null)
    {
        $path = Application::storagePath('imports') . Path::ds() . $file . '.json';
        return Json::decode($path, $associative);
    }
}

if (!function_exists('export')) {
    /**
     * Export content to a file in the "exports" directory.
     *
     * @param string $fileName The name of the file to export.
     * @param mixed $content The content to export.
     * @param bool $lock Whether to lock the file during writing.
     *
     * @return int|false The number of bytes written or false on failure.
     */
    function export($fileName, $content, $lock = false): int|false
    {
        $path = Application::storagePath('exports') . Path::ds() . $fileName;
        return File::put($path, $content, $lock);
    }
}

if (!function_exists('export_json')) {
    /**
     * Export content to a JSON file in the "exports" directory.
     *
     * @param string $fileName The name of the JSON file to export.
     * @param mixed $content The content to export.
     * @param bool $lock Whether to lock the file during writing.
     *
     * @return int|false The number of bytes written or false on failure.
     */
    function export_json($fileName, $content, $lock = false): int|false
    {
        $path = Application::storagePath('exports') . Path::ds() . $fileName . '.json';
        return File::put($path, $content, $lock);
    }
}


if (!function_exists('now')) {
    /**
     * Get the current date and time in the "Y-m-d H:i:s" format.
     *
     * @return string The current date and time.
     */
    function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('translate')) {
    /**
     * Translates a string based on the selected language.
     *
     * @param string $key The translation key.
     * @return string The translated string or the key itself if the translation is not found.
     */
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
    /**
     * Display structured data with improved formatting for debugging purposes.
     *
     * @param mixed $value The value to display.
     * @param mixed ...$values Additional values to display.
     *
     * @return void
     */
    function pre(mixed $value, array ...$values): void
    {
        echo '<style> pre {background: #2c2c2c;color: #ccc;font-size: 15px; padding: 20px} </style>';
        echo '<pre >';
        var_dump(...func_get_args());
        echo '<pre>';
    }
}
