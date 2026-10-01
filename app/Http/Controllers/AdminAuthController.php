<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\LoginRequest;

class AdminAuthController extends Controller
{
    use SendsOtp;

    public function showLogin()
    {
        if (session('admin_logged_in') && auth()->user()?->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        return view('auth.login', ['role' => 'admin']);
    }

    public function processLogin(LoginRequest $request)
    {
        $user = $request->authenticate('admin');

        $this->loginAs($user, $request->boolean('remember'));

        return redirect()->intended('/admin/dashboard');
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}
