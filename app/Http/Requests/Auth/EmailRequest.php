<?php

namespace App\Http\Requests\Auth;

use App\Http\Controllers\OtpAuthController;

/**
 * Just an email (+ portal role): used to request a login code or a password-reset code.
 */
class EmailRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'email' => self::emailRules(),
            'role' => ['sometimes', 'string', 'in:'.implode(',', OtpAuthController::ROLES)],
        ];
    }
}
