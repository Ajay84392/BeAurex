<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\EmailRequest;
use App\Http\Requests\Auth\OtpRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpAuthController extends Controller
{
    use SendsOtp;

    public const ROLES = ['customer', 'merchant', 'admin'];

    /** Shown whether or not the account exists, so emails can't be probed. */
    public const CODE_SENT_MESSAGE = 'If an account exists for this email address, we have sent a 4-digit code to it.';

    /**
     * Customer sign-up: send an OTP, and only apply the name/password once it is verified.
     */
    public function register(RegisterRequest $request)
    {
        $email = $request->validated('email');
        $user = User::where('email', $email)->where('role', 'customer')->first();

        if ($user && $user->email_verified_at) {
            return self::alreadyRegistered('customer', $email);
        }

        try {
            $user ??= User::create([
                'name' => $request->validated('name'),
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'role' => 'customer',
            ]);
        } catch (UniqueConstraintViolationException $e) {
            // A database that still allows each email only once in total (migration
            // 2026_09_30_120000 not run yet) rejects a customer account for a merchant's email.
            return self::emailUsedElsewhere($e);
        }

        $this->issueOtp($user);

        session([
            'otp_pending_email' => $user->email,
            'otp_pending_role' => 'customer',
            'otp_pending_remember' => false,
            'otp_pending_register' => [
                'name' => $request->validated('name'),
                'password' => Hash::make($request->validated('password')),
            ],
        ]);

        return redirect($this->verifyUrl('customer'))->with('status', 'We have sent a 4-digit code to '.$user->email.'.');
    }

    /**
     * Passwordless login: email a code to the account of this role, if there is one.
     * The response is the same either way.
     */
    public function sendLoginOtp(EmailRequest $request, string $role)
    {
        $email = $request->validated('email');
        $user = User::where('email', $email)->where('role', $role)->first();

        if ($user) {
            $this->issueOtp($user);
        }

        session()->forget('otp_pending_register');
        session([
            'otp_pending_email' => $email,
            'otp_pending_role' => $role,
            'otp_pending_remember' => $request->boolean('remember'),
        ]);

        return redirect($this->verifyUrl($role))->with('status', self::CODE_SENT_MESSAGE);
    }

    public function showVerify()
    {
        if (! session('otp_pending_email') || ! in_array(session('otp_pending_role'), self::ROLES)) {
            // No code was requested: back to the login page of the portal this URL belongs to.
            $role = request()->is('admin/*') ? 'admin' : (request()->is('merchant/*') ? 'merchant' : 'customer');

            return redirect($this->loginUrl($role));
        }

        return view('auth.verify-otp', [
            'email' => session('otp_pending_email'),
            'role' => session('otp_pending_role'),
            'action' => $this->verifyUrl(session('otp_pending_role')),
            'resendAction' => route('otp.resend'),
            'backUrl' => $this->loginUrl(session('otp_pending_role')),
        ]);
    }

    public function resend()
    {
        if (! session('otp_pending_email')) {
            return redirect('/customer/login')->withErrors(['email' => 'Your session has expired. Please try again.']);
        }

        if ($user = $this->pendingUser()) {
            $this->issueOtp($user);
        }

        return back()->with('status', 'If an account exists for this email address, a new code has been sent.');
    }

    public function verifyOtp(OtpRequest $request)
    {
        $role = session('otp_pending_role');

        if (! session('otp_pending_email') || ! in_array($role, self::ROLES)) {
            return redirect($this->loginUrl($role))->withErrors(['email' => 'Your session has expired. Please try again.']);
        }

        $user = $this->pendingUser();

        if (! $this->consumeOtp($user, $request->validated('otp'))) {
            return back()->withErrors(['otp' => 'Invalid or expired code. Please try again or request a new code.']);
        }

        if ($pending = session('otp_pending_register')) {
            $user->name = $pending['name'];
            $user->password = $pending['password'];
        }

        // Receiving the code proves the user owns this email.
        $user->email_verified_at ??= now();
        if ($role === 'merchant' && $user->onboarding_step === 'email_verification') {
            $user->onboarding_step = 'account_created';
        }
        $user->save();

        if ($role === 'customer') {
            Customer::forUser($user);
        }

        $remember = (bool) session('otp_pending_remember');
        session()->forget(['otp_pending_email', 'otp_pending_role', 'otp_pending_register', 'otp_pending_remember']);

        $this->loginAs($user, $remember);

        return match ($role) {
            'admin' => redirect('/admin/dashboard'),
            'merchant' => app(MerchantAuthController::class)->redirectBasedOnOnboarding($user),
            default => redirect()->intended(route('customer.home')),
        };
    }

    /**
     * Sign-up hit a unique-email rule (the email is used by an account in another portal and this
     * database does not allow that yet). Show a clear message instead of a 500 error.
     */
    public static function emailUsedElsewhere(UniqueConstraintViolationException $e)
    {
        report($e);

        return back()->withInput(request()->except(['password', 'password_confirmation']))
            ->withErrors(['email' => 'This email is already used by another BeAurex account. Please use a different email address, or log in to that account.']);
    }

    /**
     * Send someone who already has a verified account of this role to its login page.
     */
    public static function alreadyRegistered(string $role, string $email)
    {
        $login = ['admin' => '/admin', 'merchant' => '/merchant/login', 'customer' => '/customer/login'][$role];

        return redirect($login)
            ->withErrors(['email' => 'An account with this email already exists. Please log in, or use Forgot Password.'])
            ->withInput(['email' => $email]);
    }

    private function pendingUser(): ?User
    {
        $email = session('otp_pending_email');
        $role = session('otp_pending_role');

        if (! $email || ! in_array($role, self::ROLES)) {
            return null;
        }

        return User::where('email', $email)->where('role', $role)->first();
    }

    private function verifyUrl(?string $role): string
    {
        return match ($role) {
            'admin' => url('/admin/verify-otp'),
            'merchant' => url('/merchant/verify-otp'),
            default => url('/verify-otp'),
        };
    }
}
