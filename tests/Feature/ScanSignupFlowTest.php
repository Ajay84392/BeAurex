<?php

namespace Tests\Feature;

use App\Mail\LoginOtpMail;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as GoogleUser;
use Mockery;
use Tests\TestCase;

/**
 * Scanning a shop's QR while logged out: the visitor must log in or create an account first,
 * then lands on the coin popup. Google never creates an account without the user confirming it.
 */
class ScanSignupFlowTest extends TestCase
{
    use DatabaseTransactions;

    private Business $shop;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $merchant = User::create(['name' => 'Shop Owner', 'email' => 'owner@example.com', 'password' => Hash::make('Pass@1234'), 'role' => 'merchant']);
        $this->shop = Business::create(['user_id' => $merchant->id, 'name' => 'Chai Corner', 'email' => 'owner@example.com']);
    }

    private function enableGoogle(string $email, string $name = 'Riya Sen', bool $verified = true): void
    {
        config(['services.google.client_id' => 'test-id', 'services.google.client_secret' => 'test-secret']);
        $googleUser = (new GoogleUser)->setRaw(['email_verified' => $verified])->map(['id' => 'g-1', 'email' => $email, 'name' => $name]);
        $provider = Mockery::mock(Provider::class);
        $provider->shouldReceive('redirectUrl')->andReturnSelf();
        $provider->shouldReceive('user')->andReturn($googleUser);
        $provider->shouldReceive('redirect')->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));
        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    private function collectUrl(): string
    {
        return route('customer.collect', $this->shop);
    }

    public function test_logged_out_scan_asks_to_log_in_or_sign_up_first(): void
    {
        $this->get($this->collectUrl())->assertRedirect('/customer/login');
        $this->get('/customer/login')->assertOk()->assertSee('to collect your coin at')->assertSee('Chai Corner');
        $this->get('/customer/register')->assertOk()->assertSee('Chai Corner');
        $this->assertDatabaseCount('customer_visits', 0);
    }

    public function test_google_is_hidden_and_refused_until_configured(): void
    {
        config(['services.google.client_id' => null, 'services.google.client_secret' => null]);
        $this->get('/customer/login')->assertDontSee('Continue with Google');
        $this->get('/auth/google/customer')->assertRedirect('/customer/login');
        $this->get('/auth/google/customer/callback')->assertNotFound();
        $this->assertGuest();
    }

    public function test_new_google_user_must_create_the_account_then_gets_the_coin(): void
    {
        $this->enableGoogle('riya@gmail.com');
        $this->get($this->collectUrl());                       // scanned while logged out

        $this->get('/customer/login')->assertSee('Continue with Google');
        $this->get('/auth/google/customer/callback')->assertRedirect(route('google.signup'));
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'riya@gmail.com']);

        $this->get(route('google.signup'))->assertOk()->assertSee('riya@gmail.com')->assertSee('Create Account');
        $this->post('/customer/register/google', ['name' => 'Riya Sen'])->assertSessionHasErrors('terms');
        $this->assertDatabaseMissing('users', ['email' => 'riya@gmail.com']);

        $this->post('/customer/register/google', ['name' => 'Riya Sen', 'terms' => '1'])->assertRedirect($this->collectUrl());
        $user = User::where('email', 'riya@gmail.com')->where('role', 'customer')->firstOrFail();
        $this->assertNotNull($user->email_verified_at);
        $this->assertAuthenticatedAs($user);

        $this->get($this->collectUrl())->assertOk()->assertSee('Coin Collected!');
    }

    public function test_existing_customer_with_google_goes_straight_to_the_coin(): void
    {
        $user = User::create(['name' => 'Riya', 'email' => 'riya@gmail.com', 'password' => Hash::make('Pass@1234'), 'role' => 'customer']);
        $user->forceFill(['email_verified_at' => now()])->save();
        $this->enableGoogle('riya@gmail.com');

        $this->get($this->collectUrl());
        $this->get('/auth/google/customer/callback')->assertRedirect($this->collectUrl());
        $this->assertAuthenticatedAs($user);
        $this->assertSame(1, User::where('email', 'riya@gmail.com')->count());
    }

    public function test_google_account_without_verified_email_is_refused(): void
    {
        $this->enableGoogle('unverified@gmail.com', verified: false);
        $this->get('/auth/google/customer/callback')->assertRedirect('/customer/login');
        $this->assertGuest();
        $this->assertDatabaseMissing('users', ['email' => 'unverified@gmail.com']);
    }

    public function test_email_sign_up_after_scanning_lands_on_the_coin(): void
    {
        $this->get($this->collectUrl());

        $this->post('/customer/register', [
            'name' => 'Asha Rao', 'email' => 'asha@example.com',
            'password' => 'Pass@1234', 'password_confirmation' => 'Pass@1234', 'terms' => '1',
        ])->assertRedirect('/verify-otp');

        $otp = null;
        Mail::assertSent(LoginOtpMail::class, function ($mail) use (&$otp) {
            $otp = (string) $mail->otp;

            return true;
        });
        $this->post('/verify-otp', ['otp' => $otp])->assertRedirect($this->collectUrl());
        $this->get($this->collectUrl())->assertOk()->assertSee('Coin Collected!');
    }
}
