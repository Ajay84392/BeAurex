<?php

namespace App\Http\Middleware;

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
            // Remember where they were going (e.g. a scanned QR) so login can send them back.
            return redirect()->guest('/customer/login');
        }

        return NoStore::apply($next($request));
    }
}
