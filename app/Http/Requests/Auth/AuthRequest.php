<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Shared rules and messages for every auth form (login, sign-up, OTP, forgot/reset password).
 */
abstract class AuthRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Emails are compared case-insensitively, so trim and lowercase them before validating.
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => strtolower(trim($this->input('email')))]);
        }
        if (is_string($this->input('name'))) {
            $this->merge(['name' => preg_replace('/\s+/', ' ', trim($this->input('name')))]);
        }
    }

    protected static function emailRules(): array
    {
        return ['required', 'string', 'email:rfc', 'max:255'];
    }

    protected static function nameRules(): array
    {
        return ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL][\pL\s.\'-]*$/u'];
    }

    protected static function newPasswordRules(): array
    {
        // Strength first, so a weak password reports what's wrong before any mismatch.
        return ['required', 'string', 'max:64', Password::defaults(), 'confirmed'];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Please enter a valid email address.',
            'name.required' => 'Name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.max' => 'Name may not be longer than 100 characters.',
            'name.regex' => 'Name can only contain letters, spaces, dots, apostrophes and hyphens.',
            'phone.required' => 'Mobile number is required.',
            'password.required' => 'Password is required.',
            'password.max' => 'Password may not be longer than 64 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.mixed' => 'Password must contain both an uppercase and a lowercase letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one symbol.',
            'password_confirmation.required' => 'Please confirm your password.',
            'terms.accepted' => 'Please accept the Terms & Conditions and Privacy Policy.',
            'otp.required' => 'Please enter the 4-digit code.',
            'otp.digits' => 'The code must be exactly 4 digits.',
        ];
    }
}
