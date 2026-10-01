<?php

namespace App\Http\Requests\Auth;

class OtpRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'otp' => ['required', 'digits:4'],
        ];
    }
}
