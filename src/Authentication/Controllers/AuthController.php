<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Controllers;

use App\RequestValidators\TwoFactorLoginRequestValidator;
use Effectra\Core\Authentication\Contracts\AuthInterface;
use Effectra\Core\Authentication\Contracts\RequestValidatorFactoryInterface;
use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\Core\Authentication\Exceptions\ValidationException;
use Effectra\Core\Authentication\RegisterUserData;
use Effectra\Core\Authentication\Validation\UserLoginRequestValidator;
use Effectra\Core\Authentication\Validation\UserRegisterRequestValidator;
use Effectra\Core\Exceptions\HttpException;
use Effectra\Core\Request;
use Effectra\Core\Response;
use Effectra\Core\Validator;
use Effectra\Security\Hash;

class AuthController
{

    public function __construct(
        protected AuthInterface $auth,
        private readonly RequestValidatorFactoryInterface $requestValidatorFactory,
    ) {
    }

    public function login(Request $request, Response $response, $args)
    {
        $result = ['message' => 'login failed, email or password not correct'];
        $code = 400;
        $data = $this->requestValidatorFactory->make(UserLoginRequestValidator::class)->validate($request->inputs());
        if ($this->auth->attemptLogin($data)) {
            $token = $this->auth->getToken();
            $result = ['message' => 'successfully login', 'token' => $token];
            $code = 200;
        }
        return $response->json($result, $code);
    }

    public function logout(Request $request, Response $response, $args)
    {
        $this->auth->logout();

        return $response->json(['message' => 'user logout']);
    }

    public function register(Request $request, Response $response, $args)
    {
        $result = ['message' => 'registration failed'];
        $code = 400;
        $data = $this->requestValidatorFactory->make(UserRegisterRequestValidator::class)->validate($request->inputs());
        $user = $this->auth->register(new RegisterUserData($data['username'], $data['email'], $data['password']));

        if ($user) {

            $token = $this->auth->getToken();

            $result = ['message' => 'successfully user registration', 'token' => $token];
            $code = 201;
        }
        return $response->json($result, $code);
    }

    public function tokenVerify(Request $request, Response $response, $args)
    {
        $result = ['message' => 'token not validated'];
        $code = 404;

        $token = $request->getTokenFromBearer();

        $this->auth->setToken($token);

        if ($this->auth->user()) {

            $result = ['message' => 'token validated', 'token' => $token];
            $code = 200;
        }

        return $response->json($result, $code);
    }

    public function forgotPassword(Request $request, Response $response, $args)
    {
        $email = $request->input('email');

        if ($this->auth->sendForgotPasswordMail($email)) {
            return $response->json(['message' => "reset password link sended successfully to $email" ]);
        }

        return $response->json(['message' => 'reset password email sending failed'],500);
    }

    public function resetPassword(Request $request, Response $response, $args)
    {
        $token = $args->token ?? null;
        $result = ['message' => 'reset Password failed'];
        $code = 400;
        $data = $request->inputs();
        $v = new Validator($data);

        $v->rule('required', [
            'password',
            'confirm_password'
        ]);

        $v->rule('equals', 'password', 'confirm_password')->label('Confirm Password');

        if (!$v->validate()) {
            $result = [
                'errors' => $v->errors()
            ];
        } else {
            $token = $request->getTokenFromBearer();

            $this->auth->setToken($token);

            $hash = Hash::setPassword($data['password']);

            $user = $this->auth->updatePassword($this->auth->user(), $hash);

            if ($user) {

                $token = $this->auth->getToken();

                $result = ['message' => 'password successfully updated'];
                $code = 201;
            }
        }

        return $response->json($result, $code);
    }

    public function profile(Request $request, Response $response, $args)
    {
        return $response->json($this->auth->user());
    }

    public function profileUpdate(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function profileChangePassword(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function emailVerify(Request $request, Response $response, $args)
    {
        $user = $request->getAttribute('user');
        
        if ($user->getVerified()) {
            return $response->json(['message' => 'user email already verified'], 200);
        }
        $this->auth->verifyUser($user);

        return $response->json(['message' => 'user email verified'], 200);
    }
    
    public function emailResend(Request $request, Response $response, $args)
    {
        /** @var UserInterface $user */
        $user = $request->getAttribute('user');

        $this->auth->sendVerifyMail($user);

        return $response->json(['message' => 'email sended']);
    }

    public function twoFactorAuthentication(Request $request, Response $response, $args)
    {
        $data = $this->requestValidatorFactory->make(TwoFactorLoginRequestValidator::class)->validate(
            $request->getParsedBody()
        );

        if (!$this->auth->attemptTwoFactorLogin($data)) {
            throw new ValidationException(['code' => ['Invalid Code']]);
        }

        return $response->json(['message' => 'two factor applied']);
    }

    public function twoFactorRecoveryCodes(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function loginOAuth(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function loginOAuthCallback(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function deactivateAccount(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function impersonate(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function stopImpersonating(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function termsOfService(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }

    public function privacyPolicy(Request $request, Response $response, $args)
    {
        return $response->json(['message' => 'not implemented yet']);
    }
}
