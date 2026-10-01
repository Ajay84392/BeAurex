<?php

namespace App\Http\Requests\Auth;

/**
 * Confirming a new customer account that came from "Continue with Google".
 * The email comes from Google (kept in the session), so only the name and terms are asked for.
 */
class GoogleSignupRequest extends AuthRequest
{
    public function rules(): array
    {
        return [
            'name' => self::nameRules(),
            'terms' => ['accepted'],
        ];
    }
}
