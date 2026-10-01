<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileNumber;

/**
 * Customer and merchant sign-up. Merchants must also give a mobile number.
 */
class RegisterRequest extends AuthRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => self::nameRules(),
            'email' => self::emailRules(),
            'password' => self::newPasswordRules(),
            'password_confirmation' => ['required', 'string'],
            'terms' => ['accepted'],
        ];

        if ($this->is('merchant/*')) {
            $rules['phone'] = ['required', 'string', new MobileNumber];
        }

        return $rules;
    }
}
