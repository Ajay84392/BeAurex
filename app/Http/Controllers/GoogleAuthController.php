<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SendsOtp;
use App\Http\Requests\Auth\GoogleSignupRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

/**
 * "Continue with Google" for customers.
 *
 * Google only proves who the person is. An existing customer is logged in; someone new is
 * shown a "Create your account" step first, and the account is created only when they
 * confirm it. Either way they then continue to where they were going (e.g. a scanned QR).
 */
class GoogleAuthController extends Controller
{
    use SendsOtp;

    private const SIGNUP_SESSION = 'google_signup';

    public static function enabled(): bool
    {
        return filled(config('services.google.client_id')) && filled(config('services.google.client_secret'));
    }

    public function redirect(string $role)
    {
        abort_unless($role === 'customer', 404);

        if (! self::enabled()) {
            return redirect()->route('login')
                ->withErrors(['form' => 'Google sign-in is not available right now. Please log in with your email.']);
        }

        return $this->google()->redirect();
    }

    public function callback(string $role)
    {
        abort_unless($role === 'customer' && self::enabled(), 404);

        try {
            $google = $this->google()->user();
        } catch (Throwable $e) {
            Log::warning('Google sign-in failed: '.$e->getMessage());

            return redirect()->route('login')
                ->withErrors(['form' => 'Google sign-in was cancelled or failed. Please try again.']);
        }

        $email = strtolower(trim((string) $google->getEmail()));
        if ($email === '' || ($google->user['email_verified'] ?? true) === false) {
            return redirect()->route('login')
                ->withErrors(['form' => 'Your Google account has no verified email address. Please sign up with your email instead.']);
        }

        $user = User::where('email', $email)->where('role', 'customer')->first();

        // Already a customer: log straight in.
        if ($user) {
            // Google has verified this address, which also completes an unfinished email sign-up.
            $user->email_verified_at ??= now();
            $user->save();

            return $this->finish($user);
        }

        // New to BeAurex: confirm the account before it is created.
        session([self::SIGNUP_SESSION => [
            'email' => $email,
            'name' => Str::limit(trim((string) ($google->getName() ?: Str::before($email, '@'))), 100, ''),
        ]]);

        return redirect()->route('google.signup');
    }

    public function showSignup()
    {
        $profile = session(self::SIGNUP_SESSION);
        if (! $profile) {
            return redirect()->route('customer.register');
        }

        return view('auth.google-signup', ['profile' => $profile]);
    }

    public function signup(GoogleSignupRequest $request)
    {
        $profile = session(self::SIGNUP_SESSION);
        if (! $profile) {
            return redirect()->route('customer.register')
                ->withErrors(['form' => 'Your Google sign-up expired. Please continue with Google again.']);
        }

        $user = User::where('email', $profile['email'])->where('role', 'customer')->first();
        if (! $user) {
            try {
                $user = User::create([
                    'name' => $request->validated('name'),
                    'email' => $profile['email'],
                    // Google accounts log in with Google; "Forgot password" can set a password later.
                    'password' => Hash::make(Str::random(40)),
                    'role' => 'customer',
                ]);
            } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
                return OtpAuthController::emailUsedElsewhere($e);
            }
        }
        $user->email_verified_at ??= now();
        $user->save();

        session()->forget(self::SIGNUP_SESSION);

        return $this->finish($user, 'Your BeAurex account has been created.');
    }

    private function finish(User $user, ?string $status = null)
    {
        Customer::forUser($user);
        $this->loginAs($user);

        // Back to where they were going (a scanned QR goes on to the coin popup).
        return redirect()->intended(route('customer.home'))->with($status ? ['status' => $status] : []);
    }

    private function google()
    {
        // Built from the current host, so the same code works on beaurex.in and on localhost.
        return Socialite::driver('google')->redirectUrl(route('google.callback', ['role' => 'customer']));
    }
}
