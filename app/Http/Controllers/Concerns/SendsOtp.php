<?php

namespace App\Http\Controllers\Concerns;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

trait SendsOtp
{
    /** Minutes a code stays valid. */
    public static int $otpLifetime = 10;

    /** Wrong guesses allowed for one code before it is thrown away. */
    public static int $otpMaxAttempts = 5;

    /**
     * Generate a 4-digit code, store only its hash for 10 minutes, and email it.
     */
    protected function issueOtp(User $user): void
    {
        $otp = (string) random_int(1000, 9999);

        $user->otp = Hash::make($otp);
        $user->otp_expires_at = Carbon::now()->addMinutes(self::$otpLifetime);
        $user->save();
        RateLimiter::clear($this->otpAttemptKey($user));

        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            // Never log the code itself.
            Log::error('OTP mail to user #'.$user->id.' failed: '.$e->getMessage());
        }
    }

    /**
     * Check a submitted code. On success the code is cleared so it can't be reused;
     * after too many wrong guesses it is cleared too, and a new code must be requested.
     */
    protected function consumeOtp(?User $user, $otp): bool
    {
        if (! $user || ! $user->otp || ! $user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return false;
        }

        $key = $this->otpAttemptKey($user);

        if (! Hash::check((string) $otp, $user->otp)) {
            RateLimiter::hit($key, self::$otpLifetime * 60);
            if (RateLimiter::attempts($key) >= self::$otpMaxAttempts) {
                $this->clearOtp($user);
            }

            return false;
        }

        $this->clearOtp($user);

        return true;
    }

    private function clearOtp(User $user): void
    {
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();
        RateLimiter::clear($this->otpAttemptKey($user));
    }

    private function otpAttemptKey(User $user): string
    {
        return 'otp-attempts|'.$user->id;
    }

    /**
     * Log the user in for their role, dropping any other role's portal session.
     */
    protected function loginAs(User $user, bool $remember = false): void
    {
        auth()->login($user, $remember);
        request()->session()->regenerate();
        session()->forget(['admin_logged_in', 'merchant_logged_in', 'customer_logged_in']);
        session([$user->role.'_logged_in' => true]);
    }

    protected function loginUrl(?string $role): string
    {
        return match ($role) {
            'admin' => url('/admin'),
            'merchant' => url('/merchant/login'),
            default => url('/customer/login'),
        };
    }
}
