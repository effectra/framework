<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

use Effectra\Core\Authentication\Contracts\AuthInterface;
use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\Core\Authentication\Contracts\UserProviderServiceInterface;
use Effectra\Core\Authentication\Events\UserLoggedEvent;
use Effectra\Core\Authentication\Events\UserPasswordUpdatedEvent;
use Effectra\Core\Authentication\Mail\ForgotPasswordMail;
use Effectra\Core\Authentication\Mail\VerifyUserMail;
use Effectra\Core\Authentication\Services\UserLoginCodeService;
use Effectra\Core\Authentication\SignedUrl;
use Effectra\Core\Authentication\Urls\ForgetPasswordUrl;
use Effectra\Core\Authentication\Urls\VerifyUrl;
use Effectra\Core\Security\EncryptUrl;
use Effectra\Mail\Contracts\MailerInterface;
use Effectra\Security\Hash;
use Effectra\Security\Token;
use Effectra\Session\Contracts\SessionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;

/**
 * Class Auth
 *
 * Provides authentication functionality.
 */
class Auth implements AuthInterface
{
    private ?UserInterface $user = null;
    private ?string $token = null;

    public function __construct(
        private UserProviderServiceInterface $userProvider,
        private readonly SessionInterface $session,
        private readonly Token $tokenService,
        private readonly MailerInterface $mailer,
        private UserLoginCodeService $userLoginCodeService,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    private function addToken()
    {
        $this->setToken($this->tokenService->set([
            'user_id' => $this->user->getId()
        ]));
    }

    public function setToken(string $token)
    {
        $this->token = $token;
        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function user(): ?UserInterface
    {
        if ($this->user !== null) {
            return $this->user;
        }

        // $userId = $this->session->get('user');
        $userId = $this->getIdFromTokenData();

        if (!$userId) {
            return null;
        }

        $user = $this->userProvider->getById($userId);

        if (!$user) {
            return null;
        }

        $this->user = $user;

        return $this->user;
    }

    private function getIdFromTokenData(): ?string
    {
        if (!$token = $this->getToken()) {
            return null;
        }
        return $this->tokenService->get($token)?->data->user_id ?? null;
    }

    public function attemptLogin(array $credentials): bool
    {

        $user = $this->userProvider->getByCredentials($credentials);

        if (!$user || !$this->checkCredentials($user, $credentials)) {
            return false;
        }

        $this->logIn($user);

        return true;
    }

    public function checkCredentials(UserInterface $user, array $credentials): bool
    {
        return Hash::verifyPassword($credentials['password'], $user->getPassword());
    }

    public function logout()
    {
        $this->user = null;
    }

    public function register(RegisterUserData $data): UserInterface
    {
        $data->password = Hash::setPassword($data->password);

        $user = $this->userProvider->createUser($data);

        $this->logIn($user);

        $this->sendVerifyMail($user);

        return $user;
    }

    public function sendVerifyMail(UserInterface $user)
    {
        $this->mailer->send(
            new VerifyUserMail($user->email, (string) (new VerifyUrl())->create([
                'userId' => $user->getId(),
                'email' => $user->getEmail()
            ]))
        );
    }

    public function login(UserInterface $user)
    {
        $this->user = $user;
        $this->addToken();

        $this->eventDispatcher->dispatch(new UserLoggedEvent($this->user));
    }

    public function verifyUser(UserInterface $user)
    {
        return $this->userProvider->verifyUser($user);
    }

    public function updatePassword(UserInterface $user, string $password)
    {

        $user = $this->userProvider->updatePassword($user, $password);

        $this->eventDispatcher->dispatch(new UserPasswordUpdatedEvent($this->user, $password));

        $this->logIn($user);

        return $user;
    }

    public function attemptTwoFactorLogin(array $data): bool
    {
        $userId = $this->session->get('2fa');

        if (!$userId) {
            return false;
        }

        $user = $this->userProvider->getById($userId);

        if (!$user || $user->getEmail() !== $data['email']) {
            return false;
        }

        if (!$this->userLoginCodeService->verify($user, $data['code'])) {
            return false;
        }

        $this->session->forget('2fa');

        $this->logIn($user);

        $this->userLoginCodeService->deactivateAllActiveCodes($user);

        return true;
    }

    public function sendForgotPasswordMail(string $email): bool
    {
        $user = $this->userProvider->getByCredentials(['email' => $email]);
        if (!$user) {
            return false;
        }
        $this->mailer->send(
            new ForgotPasswordMail(
                $email,
                (string) (new ForgetPasswordUrl())->create($email)
            )
        );
        return true;
    }
}
