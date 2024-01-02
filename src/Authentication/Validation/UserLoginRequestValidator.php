<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Validation;

use Effectra\Core\Authentication\Contracts\RequestValidatorInterface;
use Effectra\Core\Authentication\Exceptions\ValidationException;
use Effectra\Core\Validator;

class UserLoginRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {
        $v = new Validator($data);

        $v->rule('required', [
            'email',
            'password',
        ]);

        $v->rule('email', 'email');
        $v->rule('different', 'username', 'password');

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }
        return $data;
    }
}
