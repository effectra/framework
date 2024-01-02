<?php

declare(strict_types=1);

namespace Effectra\Core\Authentication\Validation;

use Effectra\Core\Authentication\Contracts\RequestValidatorInterface;
use Effectra\Core\Authentication\Exceptions\ValidationException;
use Effectra\Core\Authentication\Models\User;
use Effectra\Core\Validator;

class UserRegisterRequestValidator implements RequestValidatorInterface
{
    public function validate(array $data): array
    {   
        $v = new Validator($data);

        $v->rule('required', [
            'username',
            'email',
            'password',
            'confirm_password'
        ]);
        $v->rule(fn ($f, $v) => User::findBy('email', $v)->isEmpty(), 'email')->message('user with given email already exists');
        $v->rule('email', 'email');
        $v->rule('equals', 'password', 'confirm_password')->label('Confirm Password');
        $v->rule('different', 'username', 'password');
        $v->rule('lengthMin', 'username', 5);

        if (!$v->validate()) {
            throw new ValidationException($v->errors());
        }
        return $data;
    }
}
