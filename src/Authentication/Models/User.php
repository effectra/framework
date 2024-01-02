<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Models;

use App\Models\User as ModelsUser;
use Effectra\Core\Authentication\Contracts\UserInterface;

class User extends ModelsUser implements UserInterface
{

    public function getId(): int|string
    {
        return $this->getEntry('id');
    }

    public function getUsername(): string
    {
        return $this->getEntry('username');
    }

    public function getEmail(): string
    {
        return $this->getEntry('email');
    }

    public function getPassword(): string
    {
        return $this->getEntry('password');
    }

    public function getVerified(): ?int
    {
        return $this->getEntry('verified');
    }

    public function setPassword(string $password): static
    {
        $this->setEntry('password', $password);
        return $this;
    }

    public function setVerified(bool $act)
    {
        return parent::setEntry('verified', $act === true ? 1 : 0 );
    }

    public function setEmailVerifiedAt(\DateTime $verifiedAt)
    {
        return parent::setEntry('email_verified_at', $verifiedAt->format($this->timestampFormat()));
    }
}
