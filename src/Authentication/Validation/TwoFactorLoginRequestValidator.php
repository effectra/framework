<?php

declare(strict_types = 1);

namespace App\RequestValidators;

use Effectra\Core\Authentication\Contracts\RequestValidatorInterface;
use Effectra\Core\Authentication\Exceptions\ValidationException;
use Valitron\Validator;

class TwoFactorLoginRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', ['email', 'code'])->message('Required field');
        $v->rule('email', 'email');

        if (! $v->validate()) {
            throw new ValidationException($v->errors());
        }

        return $data;
    }
}
