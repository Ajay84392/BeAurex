<?php

namespace App\Http\Middleware;

use App\Http\Controllers\MerchantAuthController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keep users who are already logged in to a portal away from that portal's login/register pages.
 */
class RedirectIfPortalAuthenticated
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = $request->user();

        // A merchant still waiting on email verification may go back and fix their sign-up details.
        if (! $user || $user->role !== $role || ! session($role.'_logged_in') || ($role === 'merchant' && ! $user->email_verified_at)) {
            return NoStore::apply($next($request));
        }

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'merchant' => app(MerchantAuthController::class)->redirectBasedOnOnboarding($user),
            default => redirect()->route('customer.home'),
        };
    }
}
