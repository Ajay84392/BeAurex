<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMerchantSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('merchant_logged_in') || auth()->user()?->role !== 'merchant') {
            return redirect()->guest('/merchant/login');
        }

        // A merchant must finish email verification before using the dashboard.
        if (! auth()->user()->email_verified_at) {
            return redirect()->route('merchant.verify');
        }

        // ...and the business set-up steps, so the dashboard and QR always belong to a real business.
        if (CheckMerchantOnboarding::pendingStep(auth()->user()->onboarding_step)) {
            return CheckMerchantOnboarding::resume(auth()->user());
        }

        return NoStore::apply($next($request));
    }
}
