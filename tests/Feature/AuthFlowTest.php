<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OtpAuthController;
use App\Mail\LoginOtpMail;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use DatabaseTransactions;

    private const PASSWORD = 'Pass@1234';

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        RateLimiter::clear('login|customer|same@example.com|127.0.0.1');
    }

    private function makeUser(string $role, string $email = 'same@example.com', string $password = self::PASSWORD): User
    {
        $user = User::create(['name' => ucfirst($role).' User', 'email' => $email, 'password' => Hash::make($password), 'role' => $role]);
        $user->forceFill(['email_verified_at' => now(), 'onboarding_step' => 'completed'])->save();

        return $user;
    }

    /** The code from the most recent OTP email sent to this address. */
    private function lastOtp(string $email): ?string
    {
        $otp = null;
        Mail::assertSent(LoginOtpMail::class, function ($mail) use ($email, &$otp) {
            if ($mail->hasTo($email)) {
                $otp = (string) $mail->otp;
            }

            return true;
        });

        return $otp;
    }

    private function registration(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Asha Rao',
            'email' => 'new@example.com',
            'password' => self::PASSWORD,
            'password_confirmation' => self::PASSWORD,
            'terms' => '1',
        ], $overrides);
    }

    private function login(string $role, string $email = 'same@example.com', string $password = self::PASSWORD)
    {
        return $this->post($role === 'admin' ? '/admin' : "/$role/login", ['email' => $email, 'password' => $password]);
    }

    // ── Pages ────────────────────────────────────────────────────────────────

    public function test_all_auth_pages_render_with_csrf_and_no_store(): void
    {
        foreach (['/customer/login', '/merchant/login', '/admin', '/customer/register', '/merchant/register', '/forgot-password?role=admin'] as $url) {
            $response = $this->get($url)->assertOk()->assertSee('name="_token"', false);
            $this->assertStringContainsString('no-store', $response->headers->get('Cache-Control'));
        }
        foreach (['/customer/login', '/merchant/login', '/admin'] as $url) {
            $this->get($url)->assertSee('Email OTP')->assertSee('Forgot Password?');
        }
        $this->get('/customer/register')->assertSee('name="terms"', false);
    }

    // ── Registration ─────────────────────────────────────────────────────────

    public function test_registration_rejects_empty_and_invalid_fields(): void
    {
        $this->post('/customer/register', [])->assertSessionHasErrors(['name', 'email', 'password', 'password_confirmation', 'terms']);

        $this->post('/customer/register', $this->registration(['email' => 'not-an-email']))
            ->assertSessionHasErrors(['email' => 'Please enter a valid email address.']);
        $this->post('/customer/register', $this->registration(['name' => 'A']))->assertSessionHasErrors('name');
        $this->post('/customer/register', $this->registration(['name' => '<script>x</script>']))->assertSessionHasErrors('name');
        $this->post('/customer/register', $this->registration(['terms' => null]))
            ->assertSessionHasErrors(['terms' => 'Please accept the Terms & Conditions and Privacy Policy.']);
        $this->assertDatabaseMissing('users', ['email' => 'new@example.com']);
    }

    public function test_registration_password_rules(): void
    {
        foreach (['Sh@1', 'onlyletters!', 'NoSymbol123', '12345678!', 'lowercase@123', 'UPPERCASE@123'] as $weak) {
            $this->post('/customer/register', $this->registration(['password' => $weak, 'password_confirmation' => $weak]))
                ->assertSessionHasErrors('password');
        }
        $this->post('/customer/register', $this->registration(['password_confirmation' => 'Other@1234']))
            ->assertSessionHasErrors(['password' => 'Passwords do not match.']);
    }

    public function test_merchant_registration_rejects_invalid_phone(): void
    {
        $base = $this->registration(['email' => 'shop@example.com']);
        foreach (['', '12345', '98765432101', '5876543210', 'abcdefghij', '98765-4321'] as $phone) {
            $this->post('/merchant/register', $base + ['phone' => $phone])->assertSessionHasErrors('phone');
        }
        $this->post('/merchant/register', $base + ['phone' => '98765 43210'])->assertRedirect(route('merchant.verify'));
        $this->assertSame('+91 9876543210', User::where('email', 'shop@example.com')->value('phone'));
    }

    public function test_successful_customer_registration_verifies_email_before_password_applies(): void
    {
        $this->post('/customer/register', $this->registration(['email' => '  New@Example.COM ']))->assertRedirect('/verify-otp');

        // Email is normalised; the chosen password only applies after the code is verified.
        $user = User::where('email', 'new@example.com')->where('role', 'customer')->firstOrFail();
        $this->assertFalse(Hash::check(self::PASSWORD, $user->password));
        $this->assertNotSame($this->lastOtp('new@example.com'), $user->otp, 'The code must be stored hashed');

        $this->post('/verify-otp', ['otp' => $this->lastOtp('new@example.com')])->assertRedirect(route('customer.home'));
        $this->assertTrue(Hash::check(self::PASSWORD, $user->fresh()->password));
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_duplicate_email_registration_is_sent_to_login(): void
    {
        $this->makeUser('customer', 'dup@example.com');
        $this->post('/customer/register', $this->registration(['email' => 'dup@example.com']))
            ->assertRedirect('/customer/login')->assertSessionHasErrors('email');
        $this->assertSame(1, User::where('email', 'dup@example.com')->where('role', 'customer')->count());

        // The same email may still register for a different portal.
        $this->post('/merchant/register', $this->registration(['email' => 'dup@example.com', 'phone' => '9876543210']))
            ->assertRedirect(route('merchant.verify'));
    }

    // ── Login ────────────────────────────────────────────────────────────────

    public function test_login_validation_and_generic_failure(): void
    {
        $this->makeUser('customer');

        $this->login('customer', '', '')->assertSessionHasErrors(['email' => 'Email address is required.', 'password' => 'Password is required.']);
        $this->login('customer', 'bad-email')->assertSessionHasErrors(['email' => 'Please enter a valid email address.']);

        // Wrong password and unknown account give the exact same message.
        $this->login('customer', 'same@example.com', 'Wrong@1234')->assertSessionHasErrors(['email' => \App\Http\Requests\Auth\LoginRequest::FAILED_MESSAGE]);
        $this->login('customer', 'ghost@example.com')->assertSessionHasErrors(['email' => \App\Http\Requests\Auth\LoginRequest::FAILED_MESSAGE]);
        $this->assertGuest();

        // A sign-up that never got its email code is told how to get in (Email OTP / Forgot Password).
        User::create(['name' => 'Half Done', 'email' => 'half@example.com', 'password' => Hash::make('Random@123456'), 'role' => 'customer']);
        $this->login('customer', 'half@example.com', 'Whatever@1')->assertSessionHasErrors('email');
        $this->assertStringContainsString('not verified', session('errors')->first('email'));
        $this->assertGuest();
        $this->assertGuest();
    }

    public function test_correct_login_regenerates_session(): void
    {
        $this->makeUser('customer');
        $this->get('/customer/login');
        $before = session()->getId();

        $this->login('customer')->assertRedirect(route('customer.home'));
        $this->assertAuthenticated();
        $this->assertNotSame($before, session()->getId());
    }

    public function test_repeated_failed_logins_lock_the_account_temporarily(): void
    {
        $this->makeUser('merchant', 'lock@example.com');
        RateLimiter::clear('login|merchant|lock@example.com|127.0.0.1');

        for ($i = 0; $i < 5; $i++) {
            $this->login('merchant', 'lock@example.com', 'Wrong@1234')->assertSessionHasErrors('email');
        }
        // Even the correct password is refused during the lockout.
        $this->login('merchant', 'lock@example.com')->assertSessionHasErrors('email');
        $this->assertStringContainsString('Too many login attempts', session('errors')->first('email'));
        $this->assertGuest();
    }

    public function test_same_email_has_separate_account_per_role(): void
    {
        $this->makeUser('customer', password: 'Cust@1234');
        $this->makeUser('merchant', password: 'Merch@1234');

        $this->login('customer', password: 'Cust@1234')->assertRedirect(route('customer.home'));
        $this->login('merchant', password: 'Merch@1234')->assertRedirect(route('merchant.dashboard'));
        $this->get('/customer')->assertRedirect('/customer/login');

        $this->post('/merchant/logout');
        $this->login('merchant', password: 'Cust@1234')->assertSessionHasErrors('email');
    }

    public function test_admin_login_never_creates_accounts(): void
    {
        $this->login('admin', 'intruder@example.com', 'whatever')->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'intruder@example.com']);
        $this->get('/admin/dashboard')->assertRedirect('/admin');
    }

    public function test_otp_login_works_and_does_not_reveal_unknown_emails(): void
    {
        $this->makeUser('admin');

        $known = $this->post('/admin/login/otp', ['email' => 'same@example.com']);
        $known->assertRedirect('/admin/verify-otp')->assertSessionHas('status', OtpAuthController::CODE_SENT_MESSAGE);
        $this->post('/admin/verify-otp', ['otp' => $this->lastOtp('same@example.com')])->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
        $this->post('/admin/logout');

        $unknown = $this->post('/admin/login/otp', ['email' => 'ghost@example.com']);
        $unknown->assertRedirect('/admin/verify-otp')->assertSessionHas('status', OtpAuthController::CODE_SENT_MESSAGE);
        $this->post('/admin/verify-otp', ['otp' => '1234'])->assertSessionHasErrors('otp');
        Mail::assertNotSent(LoginOtpMail::class, fn ($m) => $m->hasTo('ghost@example.com'));
    }

    public function test_otp_is_single_use_and_locks_after_five_wrong_guesses(): void
    {
        $user = $this->makeUser('customer');

        $this->post('/customer/login/otp', ['email' => 'same@example.com']);
        $code = $this->lastOtp('same@example.com');
        $wrong = $code === '1111' ? '2222' : '1111';

        for ($i = 0; $i < 5; $i++) {
            $this->post('/verify-otp', ['otp' => $wrong])->assertSessionHasErrors('otp');
        }
        // After 5 wrong guesses the real code no longer works either.
        $this->post('/verify-otp', ['otp' => $code])->assertSessionHasErrors('otp');
        $this->assertNull($user->fresh()->otp);
        $this->assertGuest();
    }

    // ── Forgot / reset password ──────────────────────────────────────────────

    public function test_forgot_password_validation_and_no_enumeration(): void
    {
        $this->makeUser('merchant');

        $this->post('/forgot-password', ['email' => '', 'role' => 'merchant'])->assertSessionHasErrors('email');
        $this->post('/forgot-password', ['email' => 'nope', 'role' => 'merchant'])->assertSessionHasErrors('email');

        $known = $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'merchant']);
        $unknown = $this->post('/forgot-password', ['email' => 'ghost@example.com', 'role' => 'merchant']);

        foreach ([$known, $unknown] as $response) {
            $response->assertRedirect(route('password.verify'))
                ->assertSessionHas('status', ForgotPasswordController::RESET_SENT_MESSAGE);
        }
        Mail::assertSent(LoginOtpMail::class, 1);
    }

    public function test_multiple_reset_requests_only_the_latest_code_works(): void
    {
        $this->makeUser('customer');

        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'customer']);
        $first = $this->lastOtp('same@example.com');
        $this->post(route('password.resend'));
        $second = $this->lastOtp('same@example.com');

        if ($first !== $second) {
            $this->post(route('password.verify.post'), ['otp' => $first])->assertSessionHasErrors('otp');
        }
        $this->post(route('password.verify.post'), ['otp' => $second])->assertRedirect(route('password.reset'));
    }

    public function test_expired_reset_code_is_rejected(): void
    {
        $this->makeUser('customer');
        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'customer']);
        $code = $this->lastOtp('same@example.com');

        $this->travel(11)->minutes();
        $this->post(route('password.verify.post'), ['otp' => $code])->assertSessionHasErrors('otp');
    }

    public function test_reset_password_validation_success_and_token_reuse(): void
    {
        $user = $this->makeUser('merchant', password: 'Old@12345');
        $other = $this->makeUser('customer', password: 'Cust@1234');

        // No verified code yet: the reset page is refused.
        $this->get(route('password.reset'))->assertRedirect(route('password.request'));

        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'merchant']);
        $code = $this->lastOtp('same@example.com');
        $this->post(route('password.verify.post'), ['otp' => $code])->assertRedirect(route('password.reset'));

        $this->post(route('password.update'), ['password' => 'short', 'password_confirmation' => 'short'])->assertSessionHasErrors('password');
        $this->post(route('password.update'), ['password' => 'New@12345', 'password_confirmation' => 'Nope@12345'])
            ->assertSessionHasErrors(['password' => 'Passwords do not match.']);

        $rememberBefore = $user->fresh()->remember_token;
        $this->post(route('password.update'), ['password' => 'New@12345', 'password_confirmation' => 'New@12345'])
            ->assertRedirect('/merchant/login');

        $this->assertTrue(Hash::check('New@12345', $user->fresh()->password));
        $this->assertNotSame($rememberBefore, $user->fresh()->remember_token);
        $this->assertTrue(Hash::check('Cust@1234', $other->fresh()->password), 'Other roles are untouched');

        // Neither the used code nor the finished reset session can be used again.
        $this->post(route('password.update'), ['password' => 'Hack@12345', 'password_confirmation' => 'Hack@12345'])
            ->assertRedirect(route('password.request'));
        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'merchant']);
        $this->post(route('password.verify.post'), ['otp' => $code === $this->lastOtp('same@example.com') ? '0000' : $code])
            ->assertSessionHasErrors('otp');
    }

    public function test_reset_window_expires(): void
    {
        $this->makeUser('customer');
        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'customer']);
        $this->post(route('password.verify.post'), ['otp' => $this->lastOtp('same@example.com')]);

        $this->travel(ForgotPasswordController::RESET_WINDOW_MINUTES + 1)->minutes();
        $this->post(route('password.update'), ['password' => 'New@12345', 'password_confirmation' => 'New@12345'])
            ->assertRedirect(route('password.request'));
    }

    // ── Logout & protected pages ─────────────────────────────────────────────

    public function test_logout_is_post_only_and_ends_the_session(): void
    {
        foreach (['customer' => ['/customer/profile', '/customer/logout', '/customer'], 'merchant' => ['/merchant/profile', '/merchant/logout', '/merchant'], 'admin' => ['/admin/profile', '/admin/logout', '/admin/dashboard']] as $role => [$profile, $logout, $home]) {
            $this->makeUser($role);
            $this->login($role);

            $page = $this->get($profile)->assertOk()->assertSee('Log Out?');
            $this->assertStringContainsString('no-store', $page->headers->get('Cache-Control'));

            $this->get($logout)->assertStatus(405);
            $this->assertAuthenticated();

            $this->post($logout)->assertRedirect('/');
            $this->assertGuest();
            $this->get($home)->assertRedirect();
        }
    }

    public function test_unverified_merchant_cannot_open_dashboard(): void
    {
        $this->post('/merchant/register', $this->registration(['email' => 'fresh@example.com', 'phone' => '9876543210']));

        $this->get('/merchant')->assertRedirect(route('merchant.verify'));
        $this->get('/merchant/register')->assertOk();
    }

    public function test_logged_in_users_are_sent_to_their_dashboard(): void
    {
        $this->makeUser('customer');
        $this->login('customer');

        $this->get('/customer/login')->assertRedirect(route('customer.home'));
        $this->get('/customer/register')->assertRedirect(route('customer.home'));
        $this->get('/merchant/register')->assertOk();
    }

    public function test_expired_form_goes_back_with_friendly_message(): void
    {
        // CSRF checks are skipped in tests, so simulate the 419 an expired token produces.
        \Illuminate\Support\Facades\Route::post('/_test-expired', fn () => abort(419))->middleware('web');

        $this->from('/customer/login')->post('/_test-expired', ['email' => 'keep@example.com', 'password' => 'secret'])
            ->assertRedirect('/customer/login')
            ->assertSessionHasErrors(['form' => 'Your session expired. Please try again.'])
            ->assertSessionHasInput('email', 'keep@example.com')
            ->assertSessionMissing('_old_input.password');
    }

    // ── Profile & QR collect ─────────────────────────────────────────────────

    public function test_profile_pages_validate_phone_and_password(): void
    {
        $customer = $this->makeUser('customer', 'c@example.com');
        $this->login('customer', 'c@example.com');
        $this->post('/customer/profile', ['phone' => '12345'])->assertSessionHasErrors('phone');
        $this->post('/customer/profile', ['name' => 'Cust Name', 'phone' => '98765 43210'])->assertSessionHasNoErrors();
        $this->assertSame('+91 9876543210', $customer->fresh()->phone);
        $this->post('/customer/logout');

        $merchant = $this->makeUser('merchant', 'm@example.com');
        Business::create(['user_id' => $merchant->id, 'name' => 'Shop', 'email' => 'm@example.com']);
        $this->login('merchant', 'm@example.com');
        $this->post('/merchant/profile', ['phone' => '5555555555'])->assertSessionHasErrors('phone');
        $this->post('/merchant/profile', ['current_password' => self::PASSWORD, 'password' => 'weak', 'password_confirmation' => 'weak'])->assertSessionHasErrors('password');
        $this->post('/merchant/profile', ['password' => 'New#pass1', 'password_confirmation' => 'New#pass1'])->assertSessionHasErrors('current_password');
        $this->post('/merchant/profile', ['current_password' => 'wrong', 'password' => 'New#pass1', 'password_confirmation' => 'New#pass1'])->assertSessionHasErrors('current_password');
        $this->post('/merchant/profile', [
            'business_name' => 'New Shop', 'pincode' => '110001', 'address' => '',
            'current_password' => self::PASSWORD, 'password' => 'New#pass1', 'password_confirmation' => 'New#pass1',
        ])->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('New#pass1', $merchant->fresh()->password));
        $this->assertSame(['New Shop', '110001', null], array_values(Business::where('user_id', $merchant->id)->first()->only('name', 'pincode', 'address')));
        $this->post('/merchant/profile', ['pincode' => '12ab'])->assertSessionHasErrors('pincode');
        $this->post('/merchant/logout');

        $admin = $this->makeUser('admin', 'a@example.com');
        $this->login('admin', 'a@example.com');
        $this->post('/admin/profile', ['phone' => '9000000001'])->assertSessionHasNoErrors();
        $this->assertSame('+91 9000000001', $admin->fresh()->phone);
    }

    public function test_scanning_merchant_qr_awards_coin_then_goes_to_claim_reward(): void
    {
        $merchant = $this->makeUser('merchant', 'shop@example.com');
        $business = Business::create(['user_id' => $merchant->id, 'name' => 'Ka-feen Cafe', 'email' => 'shop@example.com']);
        $collectUrl = route('customer.collect', $business);

        $this->get($collectUrl)->assertRedirect('/customer/login');
        $this->makeUser('customer', 'cust@example.com');
        $this->login('customer', 'cust@example.com')->assertRedirect($collectUrl);

        $this->get($collectUrl)->assertOk()->assertSee('Coin Collected!')->assertSee(route('customer.claim-reward', absolute: false));
        $this->get($collectUrl)->assertOk()->assertSee('Already Collected');
        $this->assertDatabaseCount('customer_visits', 1);

        $this->travel(CustomerDashboardController::COLLECT_COOLDOWN_MINUTES + 1)->minutes();
        $this->get($collectUrl)->assertSee('Coin Collected!');
        $this->assertDatabaseCount('customer_visits', 2);
    }
}
