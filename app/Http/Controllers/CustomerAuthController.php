<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;

class CustomerAuthController extends Controller
{
    use SendsOtp;

    public function showLogin()
    {
        return view('auth.login', ['role' => 'customer']);
    }

    public function processLogin(LoginRequest $request)
    {
        // Sign-up was started but the email code was never entered, so no password was set yet.
        // Send them back to finish it (registering again re-sends the code and sets the password).
        $pending = User::where('email', $request->validated('email'))->where('role', 'customer')->whereNull('email_verified_at')->first();
        if ($pending) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                "email" => "Your account is not verified yet. Please use the \"Email OTP\" tab to log in, or click \"Forgot Password\" to set a password.",
            ]);
        }

        $user = $request->authenticate('customer');

        $this->loginAs($user, $request->boolean('remember'));

        return redirect()->intended(route('customer.home'));
    }
}
