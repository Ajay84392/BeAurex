<?php

namespace App\Http\Middleware;

use App\Http\Controllers\MerchantAuthController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Merchant sign-up steps after email verification (account created, business info, address,
 * setup complete). Only a logged-in, verified merchant may open them; anyone else is sent to
 * the merchant login, and a merchant who already finished goes to the dashboard.
 */
class CheckMerchantOnboarding
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'merchant' || ! session('merchant_logged_in')) {
            return redirect()->guest('/merchant/login');
        }

        if (! $user->email_verified_at) {
            return redirect()->route('merchant.verify');
        }

        // Onboarding is finished: only the "setup complete" screen is still useful.
        if ($user->onboarding_step === 'completed' && ! $request->routeIs('merchant.setup-complete')) {
            return redirect()->route('merchant.dashboard');
        }

        return NoStore::apply($next($request));
    }

    /** Steps that are part of onboarding (used by CheckMerchantSession to send merchants back here). */
    public static function pendingStep(?string $step): bool
    {
        return in_array($step, ['account_created', 'business_information', 'business_address'], true);
    }

    public static function resume($user): Response
    {
        return app(MerchantAuthController::class)->redirectBasedOnOnboarding($user);
    }
}
