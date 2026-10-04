<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CustomerAuthController extends Controller
{
    use SendsOtp;

    public function showLogin()
    {
        return view('auth.portal-login', ['role' => 'customer']);
    }

    public function processLogin(LoginRequest $request)
    {
        // Sign-up was started but the email code was never entered, so no password was set yet.
        // Send them back to finish it (registering again re-sends the code and sets the password).
        $pending = User::where('email', $request->validated('email'))->where('role', 'customer')->whereNull('email_verified_at')->first();
        if ($pending) {
            throw ValidationException::withMessages([
                'email' => 'Your email is not verified yet. Click "Forgot Password?" to verify it with a code and set your password.',
            ]);
        }

        $user = $request->authenticate('customer');

        $this->loginAs($user, $request->boolean('remember'));

        return redirect()->intended(route('customer.home'));
    }
}
