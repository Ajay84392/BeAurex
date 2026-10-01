<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends AuthRequest
{
    /** Failed attempts allowed per email + IP before a lockout. */
    public const MAX_ATTEMPTS = 5;

    /** Same wording whether or not the account exists, so emails can't be probed. */
    public const FAILED_MESSAGE = 'These credentials do not match our records. Check you are on the right login page '
        .'(customer, merchant or admin each have their own password), or use Forgot Password.';

    public function rules(): array
    {
        return [
            'email' => self::emailRules(),
            'password' => ['required', 'string', 'max:64'],
        ];
    }

    /**
     * Check the credentials for the given role. Always fails with the same generic
     * message so it never reveals whether an account exists.
     */
    public function authenticate(string $role): User
    {
        $key = $this->throttleKey($role);

        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            event(new Lockout($this));

            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in '.RateLimiter::availableIn($key).' seconds.',
            ]);
        }

        $user = User::where('email', $this->input('email'))->where('role', $role)->first();

        if (! $user || ! Hash::check($this->input('password'), $user->password)) {
            RateLimiter::hit($key, 60);

            throw ValidationException::withMessages(['email' => self::FAILED_MESSAGE]);
        }

        RateLimiter::clear($key);

        return $user;
    }

    private function throttleKey(string $role): string
    {
        return 'login|'.$role.'|'.$this->input('email').'|'.$this->ip();
    }
}
