<?php

declare(strict_types=1);

namespace Effectra\Core\Middlewares;

use Effectra\Core\Application;
use Effectra\Core\Response;
use Effectra\Security\Csrf;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfFieldsMiddleware implements MiddlewareInterface
{
    protected $csrf;

    public function __construct()
    {
        $this->csrf = Application::container()->get(Csrf::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request->getMethod() !== 'GET') {

            $this->csrf->setUrl((string) $request->getUri());

            if (!$this->csrf->validate()) {
                $response = new Response(403);

                return $response->write($this->html());
            }
            $this->csrf->unsetToken();
        }

        return $handler->handle($request->withoutAttribute('eg-csrf-token-label'));
    }

    /**
     * Get the HTML content for a 404 Not Found response.
     *
     * @return string The HTML content.
     */
    public static function html(): string
    {
        return <<<'HTML'
<html><head><title>403 Invalid Request</title></head><style>body {font-family: sans-serif;text-align: center;margin: 50px;}h1 {color: #333;}hr {border: none;border-top: 1px solid #ccc;margin: 20px auto;width: 50%;}p {color: #666;}</style><body><h1>403 | Invalid Request</h1><hr><p>Possible CSRF attack detected.</p></body></html>
HTML;
    }
}
