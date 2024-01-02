<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;


use Effectra\Core\Authentication\Controllers\AuthController;
use Effectra\Core\Authentication\Middlewares\AuthJwtMiddleware;
use Effectra\Core\Authentication\Middlewares\GetUserFromHashedUriMiddleware;
use Effectra\Core\Authentication\Middlewares\ValidateSignatureMiddleware;
use Effectra\Core\Authentication\Middlewares\VerifyEmailMiddleware;
use Effectra\Router\Route;

class AuthRouter
{

    public static function handle(Route $router, string $authPath = 'auth', $controller = AuthController::class,$authMiddleware = AuthJwtMiddleware::class)
    {

        $router->post("$authPath/login", [$controller, 'login']);

        $router->post("$authPath/logout", [$controller, 'logout'])->middleware($authMiddleware);

        $router->post("$authPath/register", [$controller, 'register']);

        $router->get("$authPath/token/verify", [$controller, 'tokenVerify']);

        $router->post("$authPath/forgot-password", [$controller, 'forgotPassword']);

        $router->post("$authPath/reset-password/", [$controller, 'resetPassword'])->middleware($authMiddleware);
        
        $router->get("$authPath/profile", [$controller, 'profile'])->middleware($authMiddleware);

        $router->put("$authPath/profile/update", [$controller, 'profileUpdate'])->middleware($authMiddleware);

        $router->put("$authPath/profile/change-password", [$controller, 'profileChangePassword'])->middleware($authMiddleware);

        $router->get("$authPath/email/verify/", [$controller, 'emailVerify'])->middleware(GetUserFromHashedUriMiddleware::class);

        $router->post("$authPath/email/resend", [$controller, 'emailResend']);

        $router->post("$authPath/two-factor-authentication", [$controller, 'twoFactorAuthentication']);

        $router->post("$authPath/two-factor-recovery-codes", [$controller, 'twoFactorRecoveryCodes']);

        $router->post("$authPath/login/{provider}", [$controller, 'loginOAuth']);

        $router->post("$authPath/login/{provider}/callback", [$controller, 'loginOAuthCallback']);

        $router->post("$authPath/deactivate-account", [$controller, 'deactivateAccount']);

        // $router->post("$authPath/",[$controller,'']);

    }
}
