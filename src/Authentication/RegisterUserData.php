<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication;

class RegisterUserData
{
    public function __construct(
        public  string $username,
        public  string $email,
        public  string $password
    ) {
    }

     /**
     * Convert the object to an associative array.
     *
     * @return array The object properties as an associative array.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

}
