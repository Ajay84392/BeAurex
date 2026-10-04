<?php

namespace App\Http\Middleware;

use App\Models\Business;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('customer_logged_in') || auth()->user()?->role !== 'customer') {
            // The merchant opening their own QR link (e.g. from "Copy URL") sees the QR, not a customer login.
            $business = $request->route('business');
            if ($request->routeIs('customer.collect') && session('merchant_logged_in')
                && auth()->user()?->role === 'merchant'
                && Business::whereKey($business instanceof Business ? $business->getKey() : $business)->where('user_id', auth()->id())->exists()) {
                return redirect()->route('merchant.qr');
            }

            // Remember where they were going (e.g. a scanned QR) so login can send them back.
            return redirect()->guest('/customer/login');
        }

        return NoStore::apply($next($request));
    }
}
