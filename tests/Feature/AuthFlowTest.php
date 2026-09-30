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

        $this->post('/customer/register', ['name' => 'New Cust', 'email' => 'same@example.com', 'password' => 'mypass123'])
            ->assertRedirect('/verify-otp');

        $customer = User::where('email', 'same@example.com')->where('role', 'customer')->first();
        $this->assertNotNull($customer);
        $this->assertFalse(Hash::check('mypass123', $customer->password), 'Password must not apply before OTP is verified');
        $this->assertSame('merchant', User::where('email', 'same@example.com')->where('role', 'merchant')->value('role'));

        $this->post('/verify-otp', ['otp' => $customer->fresh()->otp])->assertRedirect(route('customer.home'));

        $customer->refresh();
        $this->assertTrue(Hash::check('mypass123', $customer->password));
        $this->assertNotNull($customer->email_verified_at);
        $this->assertDatabaseHas('customers', ['email' => 'same@example.com']);

        auth()->logout();
        $this->post('/customer/register', ['name' => 'Again', 'email' => 'same@example.com', 'password' => 'other123'])
            ->assertSessionHasErrors('email');
    }

    public function test_merchant_registration_does_not_convert_existing_customer(): void
    {
        $customer = $this->makeUser('customer');

        $this->post('/merchant/register', ['name' => 'Shop Owner', 'email' => 'same@example.com', 'phone' => '9876543210', 'password' => 'shop1234'])
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

    public function test_reset_page_requires_verified_otp(): void
    {
        $this->makeUser('customer');

        $this->post('/forgot-password', ['email' => 'same@example.com', 'role' => 'customer']);
        $this->get(route('password.reset'))->assertRedirect(route('password.request'));
        $this->post(route('password.update'), ['password' => 'hijack123', 'password_confirmation' => 'hijack123'])
            ->assertRedirect(route('password.request'));
    }
}
