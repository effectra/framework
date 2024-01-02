<?php

declare(strict_types=1);

namespace Effectra\Core\Error;

use Effectra\Core\Response;
use Effectra\Http\Foundation\ResponseFoundation;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\VarDumper\VarDumper;
use Effectra\Config\ConfigFile;
use Effectra\Router\Exception\NotFoundException;
use Effectra\Core\Application;
use Effectra\Core\Authentication\Exceptions\ValidationException;
use Effectra\Core\Exceptions\ExpiredTimeException;
use Effectra\Core\Exceptions\HttpException;
use Effectra\Core\Exceptions\WoopsException;
use Psr\Log\LoggerInterface;

/**
 * Class AppError
 *
 * The AppError class handles application-level errors and exceptions.
 */
class AppError
{
    /**
     * @var int The error reporting types.
     */
    protected int $types = E_ALL;

    /**
     * @var bool Whether to display errors.
     */
    protected bool $displayErrors = true;

    /**
     * @var LoggerInterface|null The logger instance.
     */
    protected ?LoggerInterface $logger = null;

    /**
     * @var string The default endpoint.
     */
    protected string $endpoint = 'web';

    private $error;

    /**
     * @var ErrorDisplayed The instance of ErrorDisplayed for handling errors.
     */
    protected $errorDisplayed;

    /**
     * AppError constructor.
     *
     * Initializes AppError with default configuration from the app configuration file.
     */
    public function __construct()
    {
        $config =  static::getConfig();
        $this->displayErrors = $config['display'] ?? true;
        $this->types = $config['types'] ?? E_ALL;
    }

    /**
     * Get the error configuration from the app configuration file.
     *
     * @return array The error configuration.
     */
    private static function getConfig(): array
    {
        $file = Application::configPath('app.php');
        $configFile = new ConfigFile($file);
        $config = $configFile->getSection('errors');

        return $config;
    }

    /**
     * Set the logger instance for error logging.
     *
     * @param LoggerInterface $logger The logger instance.
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;
        return  $this;
    }

    /**
     * Get the logger instance.
     *
     * @return LoggerInterface|null The logger instance.
     */
    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * Get the error handler for processing and displaying errors.
     *
     * @param mixed $error The error to be handled.
     * @return mixed The result of handling the error.
     */
    public function getErrorHandler(int $errorNumber, string $errorMsg, string $errorFile, int $errorLine)
    {

        if ($this->logger) {
            $this->logError();
        }
        return match ($this->endpoint) {
            'api' => $this->ApiResponse(),
            'cli' => $this->CliExecute(),
        };
    }

    /**
     * Get the error handler for processing and displaying errors.
     *
     * @param mixed $error The error to be handled.
     * @return mixed The result of handling the error.
     */
    public function getExceptionHandler(\Throwable $exception)
    {
        $this->error = $exception;
        if ($this->logger) {
            $this->logError();
        }
        return match ($this->endpoint) {
            'api' => $this->ApiResponse(),
            'cli' => $this->CliExecute(),
        };
    }

    /**
     * Set the endpoint for error handling (e.g., 'web', 'api').
     *
     * @param string $endpoint The endpoint.
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * Get the current endpoint for error handling.
     *
     * @return string The current endpoint.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Handle errors based on the specified endpoint.
     *
     * @param string $endpoint The endpoint for error handling.
     */
    public function handle(string $endpoint): void
    {
        $this->setEndpoint($endpoint);

        if ($this->getEndpoint() === 'web') {
            WoopsException::handle();
        } else {
            // set_error_handler([$this, 'getErrorHandler']);
            set_exception_handler([$this, 'getExceptionHandler']);
        }
    }

    /**
     * Register custom error handlers for specific exceptions.
     *
     * @return ErrorRegister The ErrorRegister instance.
     */
    public function registerErrors(): ErrorRegister
    {
        $errorRegister =  new ErrorRegister();

        $errors = [
            NotFoundException::class => function (HttpException $error) {
                return  [
                    404,
                    'message' => 'The requested resource was not found on this server.'
                ];
            },
            HttpException::class  => function (HttpException $error) {
                return  [
                    $error->getStatusCode(),
                    $error->getMessage()
                ];
            },
            ValidationException::class => function (ValidationException $error) {
                return  [
                    $error->getCode(),
                    ["errors" => $error->errors]
                ];
            },
            ExpiredTimeException::class => function (ExpiredTimeException $error) {
                return  [
                    $error->getStatusCode(),
                    ["message" => $error->getMessage()]
                ];
            },
            // ... add more error types and handlers as needed ...
        ];

        $errorRegister->setErrors($errors);

        return $errorRegister;
    }


    /**
     * Process Exception the error to extract relevant information.
     *
     * @return array An array containing error details.
     */
    public function processException(\Throwable $exception)
    {
        $names = explode('\\', get_class($exception));
        $type = end($names);
        return [
            'type' => $type,
            'error_class' => get_class($exception),
            'error_type' => $this->getErrorType($exception->getCode()),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTrace(),
        ];
    }

    /**
     * Process the error to extract relevant information.
     *
     * @return array An array containing error details.
     */
    public function processError(int $errorNumber, string $errorMsg, string $errorFile, int $errorLine)
    {

        return [
            'error_type' => $this->getErrorType($errorNumber),
            'message' => $errorMsg,
            'code' => $errorNumber,
            'file' => $errorFile,
            'line' => $errorLine,
        ];
    }

    /**
     * Generate an HTTP response for the error.
     *
     * @return ResponseInterface The HTTP response.
     */
    public function httpResponse(): ResponseInterface
    {
        $responseStatusCode = 500;
        $responseBody = $this->processException($this->error);

        foreach ($this->registerErrors()->getErrors() as $errorClass  => $callback) {
            if ($this->error::class === $errorClass) {
                [$responseStatusCode, $responseBody] = call_user_func($callback, $this->error);
            }
        }

        if ($this->displayErrors === false) {
            $responseBody = ['error' => 'Internal Server Error'];
        }

        return (new Response())->json($responseBody, $responseStatusCode);
    }

    /**
     * Handle the API response for the error.
     *
     * @return mixed The API response.
     */
    public function ApiResponse()
    {
        return ResponseFoundation::send($this->httpResponse());
    }

    /**
     * Execute CLI-specific actions for the error.
     *
     * @return void
     */
    public function CliExecute()
    {
        VarDumper::dump($this->error);
    }

    /**
     * Log the error using the configured logger.
     *
     * @return void
     */
    public function logError(): void
    {
        $errorMessage = sprintf(
            "[%s] %s in %s on line %d",
            get_class($this->error),
            $this->error->getMessage(),
            $this->error->getFile(),
            $this->error->getLine()
        );
        $this->logger->error($errorMessage);
    }



    /**
     * Get the error type based on the error number.
     *
     * @param int $errno The error number.
     * @return string The error type.
     */
    private function getErrorType(int $errno, $default = 'Unknown Error')
    {
        $errorTypes = [
            E_ERROR             => 'Fatal error',
            E_WARNING           => 'Warning',
            E_PARSE             => 'Parse error',
            E_NOTICE            => 'Notice',
            E_CORE_ERROR        => 'Core error',
            E_CORE_WARNING      => 'Core warning',
            E_COMPILE_ERROR     => 'Compile error',
            E_COMPILE_WARNING   => 'Compile warning',
            E_USER_ERROR        => 'User error',
            E_USER_WARNING      => 'User warning',
            E_USER_NOTICE       => 'User notice',
            E_STRICT            => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable error',
            E_DEPRECATED        => 'Deprecated',
            E_USER_DEPRECATED   => 'User deprecated',
        ];

        return $errorTypes[$errno] ?? $default;
    }
}
