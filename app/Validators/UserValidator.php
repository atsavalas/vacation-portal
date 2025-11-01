<?php

namespace App\Validators;

use Respect\Validation\Validator as Validator;

class UserValidator extends BaseValidator
{
    protected function rules($mode = 'create'): array
    {
        $rules = [
            'name'          => Validator::notEmpty(),
            'email'         => Validator::notEmpty()->email(),
            'username'      => Validator::notEmpty()->alnum('-_')->noWhitespace(),
            'employee_code' => Validator::digit()->length(7, 7),
            'password'      => Validator::notEmpty()->length(6, null),
            'role'          => Validator::in(['manager', 'employee']),
        ];

        if ($mode == 'edit') {
            unset($rules['password']);
        }

        return $rules;
    }
}