<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Contracts;

/**
 * Interface RequestValidatorInterface
 *
 * Defines the contract for request data validation operations.
 *
 * @package Effectra\Core\Authentication\Contracts
 */
interface RequestValidatorInterface
{
    /**
     * Validate the provided data and return the validated data.
     *
     * @param array $data
     *
     * @return array
     */
    public function validate(array $data): array;
}
