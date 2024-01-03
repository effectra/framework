<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

/**
 * Interface RequestValidatorFactoryInterface
 *
 * Defines the contract for creating instances of RequestValidatorInterface.
 *
 * @package Effectra\Core\Authentication\Contracts
 */
interface RequestValidatorFactoryInterface
{
    /**
     * Create an instance of RequestValidatorInterface based on the provided class name.
     *
     * @param string $class
     *
     * @return RequestValidatorInterface
     */
    public function make(string $class): RequestValidatorInterface;
}
