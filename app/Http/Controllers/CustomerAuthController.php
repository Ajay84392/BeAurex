<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    use SendsOtp;

    public function showLogin()
    {
        return view('auth.login', ['role' => 'customer']);
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email:rfc|max:255',
            'password' => 'required|string|max:64',
        ]);

        $user = User::where('email', $request->email)->where('role', 'customer')->first();
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        $this->loginAs($user, true); // remember by default

        return redirect()->intended(route('customer.home'));
    }
}
