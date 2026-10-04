<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Rules\MobileNumber;
use Illuminate\Validation\Rule;

/**
 * Customer and merchant sign-up. Merchants must also give a mobile number.
 *
 * An email (and a merchant's mobile number) can only be registered once per portal. A sign-up
 * that never verified its email doesn't count, so it can be finished by registering again.
 */
class RegisterRequest extends AuthRequest
{
    public const EMAIL_TAKEN = 'An account with this email already exists. Please sign in, or use Forgot Password.';

    public const PHONE_TAKEN = 'This mobile number is already registered with another business account.';

    public const REFERRAL_INVALID ='This referral code is not valid. Check it, or leave it blank.';

    public function rules(): array
    {
        $role = $this->role();

        $rules = [
            'name' => self::nameRules(),
            'email' => [...self::emailRules(), Rule::unique('users', 'email')
                ->where('role', $role)
                ->whereNotNull('email_verified_at')],
            'password' => self::newPasswordRules(),
            'password_confirmation' => ['required', 'string'],
            'terms' => ['accepted'],
        ];

        if ($role === 'merchant') {
            $rules['phone'] = ['required', 'string', new MobileNumber, function ($attribute, $value, $fail) {
                $phone = MobileNumber::format($value);
                $taken = $phone && User::where('role', 'merchant')
                    ->where('phone', $phone)
                    ->where('email', '!=', (string) $this->input('email'))
                    ->whereNotNull('email_verified_at')
                    ->exists();
                if ($taken) {
                    $fail(self::PHONE_TAKEN);
                }
            }];

            // Optional code from a friend's share link or typed in by hand.
            $rules['referral_code'] = ['nullable', 'string', 'max:20', function ($attribute, $value, $fail) {
                $referrer = User::byReferralCode($value, 'merchant');
                if (! $referrer) {
                    $fail(self::REFERRAL_INVALID);
                } elseif (strcasecmp((string) $referrer->email, (string) $this->input('email')) === 0) {
                    $fail('You cannot use your own referral code.');
                }
            }];
        }

        return $rules;
    }

    public function messages(): array
    {
        return parent::messages() + ['email.unique' => self::EMAIL_TAKEN];
    }

    /** Which portal this sign-up is for. */
    public function role(): string
    {
        return $this->is('merchant/*') ? 'merchant' : 'customer';
    }
}
