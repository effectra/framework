<?php

declare(strict_types = 1);

namespace Effectra\Core\Authentication\Contracts;

interface RequestValidatorFactoryInterface
{
    public function make(string $class): RequestValidatorInterface;
}