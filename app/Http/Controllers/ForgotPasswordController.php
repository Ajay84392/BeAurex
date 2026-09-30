<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    use SendsOtp;

    public function showForgotForm(Request $request)
    {
        $role = in_array($request->query('role'), OtpAuthController::ROLES) ? $request->query('role') : 'customer';

        return view('auth.forgot-password', ['role' => $role, 'loginUrl' => $this->loginUrl($role)]);
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:'.implode(',', OtpAuthController::ROLES),
        ]);

        $user = User::where('email', $request->email)->where('role', $request->role)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'We could not find a '.$request->role.' account with that email address.'])->withInput();
        }

        $this->issueOtp($user);

        session(['reset_email' => $user->email, 'reset_role' => $user->role]);
        session()->forget('reset_otp_verified');

        return redirect()->route('password.verify');
    }

    public function showVerifyForm()
    {
        if (! session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', [
            'email' => session('reset_email'),
            'role' => session('reset_role'),
            'action' => route('password.verify.post'),
            'resendAction' => route('password.resend'),
            'backUrl' => route('password.request', ['role' => session('reset_role')]),
        ]);
    }

    public function resendOtp()
    {
        $user = $this->resetUser();

        if (! $user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        $this->issueOtp($user);

        return back()->with('status', 'A new OTP has been sent to '.$user->email.'.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:4']);

        $user = $this->resetUser();

        if (! $user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Session expired. Please try again.']);
        }

        if (! $this->consumeOtp($user, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        session(['reset_otp_verified' => true]);

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        if (! session('reset_email') || ! session('reset_otp_verified')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        if (! session('reset_otp_verified') || ! ($user = $this->resetUser())) {
            return redirect()->route('password.request');
        }

        $user->password = Hash::make($request->password);
        $user->email_verified_at ??= now();
        $user->save();

        session()->forget(['reset_email', 'reset_role', 'reset_otp_verified']);

        return redirect($this->loginUrl($user->role))->with('status', 'Password reset successfully. Please login.');
    }

    private function resetUser(): ?User
    {
        $email = session('reset_email');

        if (! $email) {
            return null;
        }

        return User::where('email', $email)->where('role', session('reset_role', 'customer'))->first();
    }
}
