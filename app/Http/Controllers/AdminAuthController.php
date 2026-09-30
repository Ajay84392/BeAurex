<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

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

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc|max:255',
            'password' => 'required|string|max:64',
        ]);

        $user = User::where('email', $request->email)->where('role', 'admin')->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $this->loginAs($user, $request->boolean('remember'));

        return redirect('/admin/dashboard');
    }

    public function logout()
    {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    }
}
