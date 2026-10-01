<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\OtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use SendsOtp;

    /** Minutes the user has to choose a new password after verifying the code. */
    public const RESET_WINDOW_MINUTES = 15;

    public const RESET_SENT_MESSAGE = 'If an account exists for this email address, we have sent password reset instructions.';

    public function showForgotForm(Request $request)
    {
        $role = in_array($request->query('role'), OtpAuthController::ROLES) ? $request->query('role') : 'customer';

        return view('auth.forgot-password', ['role' => $role, 'loginUrl' => $this->loginUrl($role)]);
    }

    /**
     * Email a reset code if the account exists. The response is identical either way,
     * so this can't be used to find out which emails are registered.
     */
    public function sendOtp(EmailRequest $request)
    {
        $email = $request->validated('email');
        $role = $request->validated('role') ?? 'customer';

        if ($user = User::where('email', $email)->where('role', $role)->first()) {
            $this->issueOtp($user);
        }

        session(['reset_email' => $email, 'reset_role' => $role]);
        session()->forget(['reset_verified_user', 'reset_verified_at']);

        return redirect()->route('password.verify')->with('status', self::RESET_SENT_MESSAGE);
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
        if (! session('reset_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Your session has expired. Please try again.']);
        }

        if ($user = $this->resetUser()) {
            $this->issueOtp($user);
        }

        return back()->with('status', 'If an account exists for this email address, a new code has been sent.');
    }

    public function verifyOtp(OtpRequest $request)
    {
        if (! session('reset_email')) {
            return redirect()->route('password.request')->withErrors(['email' => 'Your session has expired. Please try again.']);
        }

        $user = $this->resetUser();

        if (! $this->consumeOtp($user, $request->validated('otp'))) {
            return back()->withErrors(['otp' => 'Invalid or expired code. Please try again or request a new code.']);
        }

        // The code is now used up; this session may set a new password for this user for a short time.
        session(['reset_verified_user' => $user->id, 'reset_verified_at' => now()->timestamp]);

        return redirect()->route('password.reset');
    }

    public function showResetForm()
    {
        if (! $this->verifiedResetUser()) {
            return redirect()->route('password.request')->withErrors(['email' => 'Your reset link has expired. Please request a new code.']);
        }

        return view('auth.reset-password');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $user = $this->verifiedResetUser();

        if (! $user) {
            return redirect()->route('password.request')->withErrors(['email' => 'Your reset link has expired. Please request a new code.']);
        }

        $user->password = Hash::make($request->validated('password'));
        $user->email_verified_at ??= now();
        // Invalidate "remember me" cookies and every existing login for this account.
        $user->setRememberToken(Str::random(60));
        $user->save();

        if (config('session.driver') === 'database') {
            DB::table(config('session.table', 'sessions'))->where('user_id', $user->id)->delete();
        }

        session()->forget(['reset_email', 'reset_role', 'reset_verified_user', 'reset_verified_at']);

        return redirect($this->loginUrl($user->role))->with('status', 'Your password has been reset. Please log in with your new password.');
    }

    private function resetUser(): ?User
    {
        $email = session('reset_email');

        if (! $email) {
            return null;
        }

        return User::where('email', $email)->where('role', session('reset_role', 'customer'))->first();
    }

    /**
     * The user whose code was verified in this session, if that happened within the reset window.
     */
    private function verifiedResetUser(): ?User
    {
        $id = session('reset_verified_user');
        $at = session('reset_verified_at');

        if (! $id || ! $at || now()->timestamp - $at > self::RESET_WINDOW_MINUTES * 60) {
            return null;
        }

        return User::find($id);
    }
}
