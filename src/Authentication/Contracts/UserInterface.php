<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

interface UserInterface
{
    public function getId(): int|string;
    public function getUsername(): string;
    public function getEmail(): string;
    public function getPassword(): string;
    public function getVerified(): ?int;
    public function setPassword(string $password): static;
    public function setVerified(bool $act);
    public function setEmailVerifiedAt(\DateTime $verifiedAt);
    public function update(): bool; 
}