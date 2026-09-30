<?php

namespace Tests\Feature;

use App\Mail\LoginOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    private function makeUser(string $role, string $email = 'same@example.com', string $password = 'secret123'): User
    {
        $user = User::create([
            'name' => ucfirst($role).' User',
            'email' => $email,
            'password' => Hash::make($password),
            'role' => $role,
        ]);
        $user->forceFill(['email_verified_at' => now(), 'onboarding_step' => 'completed'])->save();

        return $user;
    }

    public function test_login_pages_render_with_shared_theme(): void
    {
        foreach (['/customer/login', '/merchant/login', '/admin'] as $url) {
            $this->get($url)->assertOk()->assertSee('Email OTP')->assertSee('Forgot Password?');
        }
        $this->get('/forgot-password?role=merchant')->assertOk()->assertSee('Merchant Portal');
        $this->get('/customer/register')->assertOk();
        $this->get('/merchant/register')->assertOk();
    }

    public function test_same_email_can_hold_one_account_per_role_with_separate_passwords(): void
    {
        $this->makeUser('customer', password: 'cust-pass1');
        $this->makeUser('merchant', password: 'merch-pass1');
        $this->makeUser('admin', password: 'admin-pass1');

        $this->post('/customer/login', ['email' => 'same@example.com', 'password' => 'cust-pass1'])->assertRedirect(route('customer.home'));
        $this->assertSame('customer', auth()->user()->role);

        $this->post('/merchant/login', ['email' => 'same@example.com', 'password' => 'merch-pass1'])->assertRedirect(route('merchant.dashboard'));
        $this->assertSame('merchant', auth()->user()->role);
        // Switching role drops the other portal's access.
        $this->get('/customer')->assertRedirect('/customer/login');

        $this->post('/admin', ['email' => 'same@example.com', 'password' => 'admin-pass1'])->assertRedirect('/admin/dashboard');
        $this->assertSame('admin', auth()->user()->role);

        // The customer password doesn't open the merchant account.
        auth()->logout();
        $this->post('/merchant/login', ['email' => 'same@example.com', 'password' => 'cust-pass1'])->assertSessionHasErrors('email');
    }

    public function test_admin_login_rejects_unknown_email_and_does_not_create_admins(): void
    {
        $this->post('/admin', ['email' => 'intruder@example.com', 'password' => 'whatever'])->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'intruder@example.com']);
        $this->get('/admin/dashboard')->assertRedirect('/admin');
    }

    public function test_otp_login_for_each_role(): void
    {
        foreach (['customer' => route('customer.home'), 'merchant' => route('merchant.dashboard'), 'admin' => '/admin/dashboard'] as $role => $home) {
            $user = $this->makeUser($role);

            $this->post("/$role/login/otp", ['email' => 'same@example.com'])->assertRedirect();
            Mail::assertSent(LoginOtpMail::class, fn ($m) => $m->hasTo('same@example.com'));

            $verifyUrl = ['customer' => '/verify-otp', 'merchant' => '/merchant/verify-otp', 'admin' => '/admin/verify-otp'][$role];
            $this->get($verifyUrl)->assertOk()->assertSee('same@example.com');

            $this->post($verifyUrl, ['otp' => '0000'])->assertSessionHasErrors('otp');
            $this->post($verifyUrl, ['otp' => $user->fresh()->otp])->assertRedirect($home);
            $this->assertSame($user->id, auth()->id());
            $this->assertNull($user->fresh()->otp);

            auth()->logout();
        }
    }

    public function test_otp_login_fails_for_role_without_account(): void
    {
        $this->makeUser('customer');

        $this->post('/merchant/login/otp', ['email' => 'same@example.com'])->assertSessionHasErrors('email');
        Mail::assertNothingSent();
    }

    public function test_customer_registration_verifies_email_then_applies_password(): void
    {
        $this->makeUser('merchant');

        $this->post('/customer/register', ['name' => 'New Cust', 'email' => 'same@example.com', 'password' => 'Mypass@123', 'password_confirmation' => 'Mypass@123'])
            ->assertRedirect('/verify-otp');

        $customer = User::where('email', 'same@example.com')->where('role', 'customer')->first();
        $this->assertNotNull($customer);
        $this->assertFalse(Hash::check('Mypass@123', $customer->password), 'Password must not apply before OTP is verified');
        $this->assertSame('merchant', User::where('email', 'same@example.com')->where('role', 'merchant')->value('role'));

        $this->post('/verify-otp', ['otp' => $customer->fresh()->otp])->assertRedirect(route('customer.home'));

        $customer->refresh();
        $this->assertTrue(Hash::check('Mypass@123', $customer->password));
        $this->assertNotNull($customer->email_verified_at);
        $this->assertDatabaseHas('customers', ['email' => 'same@example.com']);

        auth()->logout();
        $this->post('/customer/register', ['name' => 'Again', 'email' => 'same@example.com', 'password' => 'Other@123', 'password_confirmation' => 'Other@123'])
            ->assertSessionHasErrors('email');
    }

    public function test_merchant_registration_does_not_convert_existing_customer(): void
    {
        $customer = $this->makeUser('customer');

        $this->post('/merchant/register', ['name' => 'Shop Owner', 'email' => 'same@example.com', 'phone' => '9876543210', 'password' => 'Shop@1234', 'password_confirmation' => 'Shop@1234'])
            ->assertRedirect(route('merchant.verify'));

        $this->assertSame('customer', $customer->fresh()->role);
        $merchant = User::where('email', 'same@example.com')->where('role', 'merchant')->first();
        $this->assertNotNull($merchant);

        $this->get(route('merchant.verify'))->assertOk();
        $this->post(route('merchant.verify'), ['otp' => $merchant->fresh()->otp])->assertRedirect(route('merchant.created'));
    }

    public function test_forgot_password_resets_only_the_chosen_role(): void
    {
        $customer = $this->makeUser('customer', password: 'old-cust');
        $merchant = $this->makeUser('merchant', password: 'old-merch');

        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'merchant'])->assertRedirect(route('password.verify'));
        $this->get(route('password.verify'))->assertOk();
        $this->post(route('password.verify.post'), ['otp' => $merchant->fresh()->otp])->assertRedirect(route('password.reset'));
        $this->get(route('password.reset'))->assertOk();
        $this->post(route('password.update'), ['password' => 'new-merch1', 'password_confirmation' => 'new-merch1'])
            ->assertRedirect('/merchant/login');

        $this->assertTrue(Hash::check('new-merch1', $merchant->fresh()->password));
        $this->assertTrue(Hash::check('old-cust', $customer->fresh()->password));
    }

    public function test_weak_passwords_are_rejected_everywhere(): void
    {
        $base = ['name' => 'Asha Rao', 'email' => 'new@example.com'];

        foreach (['short1!', 'onlyletters!', 'NoSymbol123', '12345678!', 'Pass@123x'] as $i => $password) {
            $response = $this->post('/customer/register', $base + ['password' => $password, 'password_confirmation' => $password]);
            if ($password === 'Pass@123x') {
                $response->assertRedirect('/verify-otp');
            } else {
                $response->assertSessionHasErrors('password');
            }
        }

        $this->post('/customer/register', $base + ['password' => 'Pass@1234', 'password_confirmation' => 'Pass@9999'])
            ->assertSessionHasErrors('password');
    }

    public function test_reset_password_enforces_policy(): void
    {
        $user = $this->makeUser('customer');
        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'customer']);
        $this->post(route('password.verify.post'), ['otp' => $user->fresh()->otp]);

        $this->post(route('password.update'), ['password' => 'weakpass', 'password_confirmation' => 'weakpass'])
            ->assertSessionHasErrors('password');
        $this->post(route('password.update'), ['password' => 'Strong@12', 'password_confirmation' => 'Strong@12'])
            ->assertRedirect('/customer/login');
    }

    public function test_merchant_mobile_number_must_be_ten_valid_digits(): void
    {
        $base = ['name' => 'Shop Owner', 'email' => 'shop@example.com', 'password' => 'Shop@1234', 'password_confirmation' => 'Shop@1234'];

        foreach (['12345', '98765432101', '5876543210', 'abcdefghij', '98765-4321'] as $phone) {
            $this->post('/merchant/register', $base + ['phone' => $phone])->assertSessionHasErrors('phone');
        }

        $this->post('/merchant/register', $base + ['phone' => '98765 43210'])->assertRedirect(route('merchant.verify'));
        $this->assertSame('+91 9876543210', User::where('email', 'shop@example.com')->value('phone'));
    }

    public function test_existing_account_is_sent_to_login_instead_of_signing_up_again(): void
    {
        $this->makeUser('merchant');

        $this->post('/merchant/register', ['name' => 'Dup', 'email' => 'same@example.com', 'phone' => '9876543210', 'password' => 'Shop@1234', 'password_confirmation' => 'Shop@1234'])
            ->assertRedirect('/merchant/login')
            ->assertSessionHasErrors('email');
        $this->assertSame(1, User::where('email', 'same@example.com')->where('role', 'merchant')->count());
    }

    public function test_logged_in_users_are_sent_to_their_dashboard_from_login_and_register(): void
    {
        $this->makeUser('customer', password: 'Cust@1234');
        $this->post('/customer/login', ['email' => 'same@example.com', 'password' => 'Cust@1234']);

        $this->get('/customer/login')->assertRedirect(route('customer.home'));
        $this->get('/customer/register')->assertRedirect(route('customer.home'));
        $this->post('/customer/register', ['name' => 'X Y', 'email' => 'other@example.com', 'password' => 'Pass@1234', 'password_confirmation' => 'Pass@1234'])
            ->assertRedirect(route('customer.home'));
        // Other portals stay reachable, since the same email may hold a merchant account too.
        $this->get('/merchant/register')->assertOk();

        $this->makeUser('merchant', password: 'Merch@1234');
        $this->post('/merchant/login', ['email' => 'same@example.com', 'password' => 'Merch@1234']);
        $this->get('/merchant/login')->assertRedirect(route('merchant.dashboard'));
        $this->get('/merchant/register')->assertRedirect(route('merchant.dashboard'));
    }

    public function test_unverified_merchant_can_go_back_to_fix_sign_up(): void
    {
        $this->post('/merchant/register', ['name' => 'Shop Owner', 'email' => 'typo@example.com', 'phone' => '9876543210', 'password' => 'Shop@1234', 'password_confirmation' => 'Shop@1234']);

        $this->get('/merchant/register')->assertOk();
    }

    public function test_logout_confirmation_popup_and_full_logout_for_each_portal(): void
    {
        foreach (['customer' => ['/customer/profile', '/customer/logout', '/customer'], 'merchant' => ['/merchant/profile', '/merchant/logout', '/merchant'], 'admin' => ['/admin/profile', '/admin/logout', '/admin/dashboard']] as $role => [$profile, $logout, $home]) {
            $this->makeUser($role, password: 'Pass@1234');
            $this->post($role === 'admin' ? '/admin' : "/$role/login", ['email' => 'same@example.com', 'password' => 'Pass@1234']);

            $this->get($profile)->assertOk()->assertSee('Log Out?')->assertSee('Are you sure you want to log out');

            $this->get($logout)->assertRedirect('/');
            $this->assertGuest();
            $this->get($home)->assertRedirect();
        }
    }

    public function test_scanning_merchant_qr_awards_coin_shows_popup_then_goes_to_claim_reward(): void
    {
        $merchant = $this->makeUser('merchant', 'shop@example.com', 'Pass@1234');
        $business = \App\Models\Business::create(['user_id' => $merchant->id, 'name' => 'Ka-feen Cafe', 'email' => 'shop@example.com']);

        // The merchant's QR points at their own collect page.
        $this->post('/merchant/login', ['email' => 'shop@example.com', 'password' => 'Pass@1234']);
        $collectUrl = route('customer.collect', $business);
        $this->get('/merchant')->assertOk()->assertSee(urlencode($collectUrl), false);
        $this->get('/merchant/logout');

        // A logged-out customer scanning it is sent to login, then straight back to collect.
        $this->get($collectUrl)->assertRedirect('/customer/login');
        $this->makeUser('customer', 'cust@example.com', 'Pass@1234');
        $this->post('/customer/login', ['email' => 'cust@example.com', 'password' => 'Pass@1234'])->assertRedirect($collectUrl);

        $this->get($collectUrl)->assertOk()
            ->assertSee('Coin Collected!')->assertSee('Ka-feen Cafe')
            ->assertSee(route('customer.claim-reward'));
        $this->assertDatabaseCount('customer_visits', 1);

        // Scanning again right away doesn't award another coin.
        $this->get($collectUrl)->assertOk()->assertSee('Already Collected');
        $this->assertDatabaseCount('customer_visits', 1);

        // After the cooldown, the next visit earns another coin.
        $this->travel(\App\Http\Controllers\CustomerDashboardController::COLLECT_COOLDOWN_MINUTES + 1)->minutes();
        $this->get($collectUrl)->assertOk()->assertSee('Coin Collected!');
        $this->assertDatabaseCount('customer_visits', 2);

        $this->get('/customer/collect/999999')->assertNotFound();
    }

    public function test_profile_pages_save_with_phone_and_password_rules(): void
    {
        $customer = $this->makeUser('customer', 'c@example.com', 'Pass@1234');
        $this->post('/customer/login', ['email' => 'c@example.com', 'password' => 'Pass@1234']);
        $this->post('/customer/profile', ['phone' => '12345'])->assertSessionHasErrors('phone');
        $this->post('/customer/profile', ['name' => 'Cust Name', 'phone' => '98765 43210'])->assertSessionHasNoErrors();
        $this->assertSame('+91 9876543210', $customer->fresh()->phone);
        $this->get('/customer/logout');

        $merchant = $this->makeUser('merchant', 'm@example.com', 'Pass@1234');
        \App\Models\Business::create(['user_id' => $merchant->id, 'name' => 'Shop', 'email' => 'm@example.com']);
        $this->post('/merchant/login', ['email' => 'm@example.com', 'password' => 'Pass@1234']);
        $this->post('/merchant/profile', ['phone' => '5555555555'])->assertSessionHasErrors('phone');
        $this->post('/merchant/profile', ['phone' => '9123456789'])->assertSessionHasNoErrors();
        $this->post('/merchant/profile', ['current_password' => 'Pass@1234', 'password' => 'weak', 'password_confirmation' => 'weak'])->assertSessionHasErrors('password');
        $this->get('/merchant/logout');

        $admin = $this->makeUser('admin', 'a@example.com', 'Pass@1234');
        $this->post('/admin', ['email' => 'a@example.com', 'password' => 'Pass@1234']);
        $this->post('/admin/profile', ['phone' => '9000000001'])->assertSessionHasNoErrors();
        $this->assertSame('+91 9000000001', $admin->fresh()->phone);
    }

    public function test_reset_page_requires_verified_otp(): void
    {
        $this->makeUser('customer');

        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'customer']);
        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
        $this->post(route('password.update'), ['password' => 'Hijack@123', 'password_confirmation' => 'Hijack@123'])
            ->assertRedirect(route('password.request'));
    }
}
