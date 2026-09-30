<?php

namespace App\Http\Controllers\Concerns;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

trait SendsOtp
{
    /**
     * Generate a 4-digit OTP for the user, store it for 10 minutes and email it.
     */
    protected function issueOtp(User $user): void
    {
        $otp = (string) random_int(1000, 9999);

        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        try {
            Mail::to($user->email)->send(new LoginOtpMail($otp));
        } catch (\Exception $e) {
            Log::error('OTP mail to '.$user->email.' failed: '.$e->getMessage());
        }
    }

    /**
     * Check the submitted OTP against the user's stored one and clear it on success.
     */
    protected function consumeOtp(?User $user, $otp): bool
    {
        if (! $user || ! $user->otp || $user->otp !== (string) $otp || ! $user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return false;
        }

        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        return true;
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
