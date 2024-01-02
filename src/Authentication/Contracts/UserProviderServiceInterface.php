<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

use Effectra\Core\Authentication\RegisterUserData;

interface UserProviderServiceInterface
{

    public function getById(int|string $userId): ?UserInterface;

    public function getByCredentials(array $credentials): ?UserInterface;

    public function createUser(RegisterUserData $data): UserInterface;

    public function verifyUser(UserInterface $user): ?UserInterface;

    public function updatePassword(UserInterface $user, string $password): UserInterface;
}
