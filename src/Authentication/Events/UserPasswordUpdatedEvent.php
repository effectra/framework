<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Events;

use Effectra\Core\Authentication\Contracts\UserInterface;
use Effectra\EventDispatcher\Event;

class UserPasswordUpdatedEvent extends Event
{
    
    public function __construct(private readonly UserInterface $user,private readonly string $password) {
       
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }
    
    public function getPassword(): string
    {
        return $this->password;
    }
}