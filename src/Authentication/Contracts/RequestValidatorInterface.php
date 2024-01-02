<?php

declare(strict_types = 1);

namespace Effectra\Core\Authentication\Contracts;

interface RequestValidatorInterface
{
    public function validate(array $data): array;
}