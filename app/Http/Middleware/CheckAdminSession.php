<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('admin_logged_in') || auth()->user()?->role !== 'admin') {
            return redirect()->guest('/admin');
        }

        return NoStore::apply($next($request));
    }
}
