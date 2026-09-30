<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class OtpAuthController extends Controller
{
    use SendsOtp;

    public const ROLES = ['customer', 'merchant', 'admin'];

    /**
     * Customer sign-up: send an OTP, and only apply the name/password once it is verified.
     */
    public function register(Request $request)
    {
        $request->validate(self::registrationRules(), self::registrationMessages());

        $user = User::where('email', $request->email)->where('role', 'customer')->first();

        if ($user && $user->email_verified_at) {
            return self::alreadyRegistered('customer', $request->email);
        }

        $user ??= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(32)),
            'role' => 'customer',
        ]);

        $this->issueOtp($user);

        session([
            'otp_pending_email' => $user->email,
            'otp_pending_role' => 'customer',
            'otp_pending_register' => [
                'name' => $request->name,
                'password' => Hash::make($request->password),
            ],
        ]);

        return redirect($this->verifyUrl('customer'));
    }

    /**
     * Passwordless login: email an OTP to an existing account of the given role.
     */
    public function sendLoginOtp(Request $request, string $role)
    {
        abort_unless(in_array($role, self::ROLES), 404);

        $request->validate(['email' => 'required|email:rfc|max:255']);

        $user = User::where('email', $request->email)->where('role', $role)->first();

        if (! $user) {
            return back()->withErrors(['email' => 'No '.$role.' account found with this email.'])->withInput()->with('otp_mode', true);
        }

        $this->issueOtp($user);

        session()->forget('otp_pending_register');
        session([
            'otp_pending_email' => $user->email,
            'otp_pending_role' => $role,
            'otp_pending_remember' => $request->boolean('remember'),
        ]);

        return redirect($this->verifyUrl($role));
    }

    public function showVerify()
    {
        if (! session('otp_pending_email') || ! session('otp_pending_role')) {
            return redirect('/customer/login');
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
        $user = $this->pendingUser();

        if (! $user) {
            return redirect('/customer/login')->withErrors(['email' => 'Session expired. Please login again.']);
        }

        $this->issueOtp($user);

        return back()->with('status', 'A new OTP has been sent to '.$user->email.'.');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|digits:4']);

        $role = session('otp_pending_role');
        $user = $this->pendingUser();

        if (! $user) {
            return redirect($this->loginUrl($role))->withErrors(['email' => 'Session expired. Please login again.']);
        }

        if (! $this->consumeOtp($user, $request->otp)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        if ($pending = session('otp_pending_register')) {
            $user->name = $pending['name'];
            $user->password = $pending['password'];
        }

        // Receiving the OTP proves the user owns this email.
        $user->email_verified_at ??= now();
        if ($role === 'merchant' && $user->onboarding_step === 'email_verification') {
            $user->onboarding_step = 'account_created';
        }
        $user->save();

        if ($role === 'customer') {
            Customer::firstOrCreate(
                ['email' => $user->email],
                ['name' => $user->name, 'phone' => $user->phone ?: '00000'.rand(10000, 99999)]
            );
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
     * Validation shared by customer and merchant sign-up.
     */
    public static function registrationRules(array $extra = []): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100', 'regex:/^[\pL\s.\'-]+$/u'],
            'email' => ['required', 'string', 'email:rfc', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
            ...$extra,
        ];
    }

    public static function registrationMessages(): array
    {
        return [
            'name.regex' => 'Name can only contain letters, spaces, dots, apostrophes and hyphens.',
            'password.confirmed' => 'The passwords do not match.',
        ];
    }

    /**
     * Send someone who already has a verified account of this role to its login page.
     */
    public static function alreadyRegistered(string $role, string $email)
    {
        $login = ['admin' => '/admin', 'merchant' => '/merchant/login', 'customer' => '/customer/login'][$role];

        return redirect($login)
            ->withErrors(['email' => 'You already have a '.$role.' account with this email. Please login below, or use Forgot Password.'])
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
