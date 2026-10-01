<?php

namespace App\Http\Requests\Auth;

class ResetPasswordRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'password' => self::newPasswordRules(),
            'password_confirmation' => ['required', 'string'],
        ];
    }
}
