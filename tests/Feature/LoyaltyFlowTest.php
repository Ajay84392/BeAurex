<?php

namespace Tests\Feature;

use App\Http\Controllers\CustomerDashboardController;
use App\Models\Business;
use App\Models\Customer;
use App\Models\Offer;
use App\Models\RewardRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Scan → coins → claim → merchant approval, and what each portal shows about it.
 */
class LoyaltyFlowTest extends TestCase
{
    use DatabaseTransactions;

    private function user(string $role, string $email, array $attrs = []): User
    {
        $user = User::create(['name' => ucfirst($role).' Person', 'email' => $email, 'password' => Hash::make('Pass@1234'), 'role' => $role]);
        $user->forceFill($attrs + ['email_verified_at' => now(), 'onboarding_step' => 'completed'])->save();

        return $user;
    }

    private function as(User $user): static
    {
        return $this->actingAs($user)->withSession([$user->role.'_logged_in' => true]);
    }

    /** Scan the shop's QR $times times, waiting out the anti-farming cooldown between scans. */
    private function scan(User $customer, Business $shop, int $times = 1): void
    {
        for ($i = 0; $i < $times; $i++) {
            $this->as($customer)->get(route('customer.collect', $shop))->assertOk()->assertSee('Coin Collected!');
            $this->travel(CustomerDashboardController::COLLECT_COOLDOWN_MINUTES + 1)->minutes();
        }
    }

    public function test_full_scan_claim_and_approval_flow(): void
    {
        $merchant = $this->user('merchant', 'shop@example.com');
        $shop = Business::create(['user_id' => $merchant->id, 'name' => 'Chai Corner', 'email' => 'shop@example.com', 'category' => 'Cafe']);
        $offer = Offer::create(['business_id' => $shop->id, 'title' => 'Free Chai', 'description' => 'One cup', 'orex_coins' => 3, 'expiry' => 30]);
        $customer = $this->user('customer', 'cust@example.com');

        // Two coins: not enough yet.
        $this->scan($customer, $shop, 2);
        $this->as($customer)->get('/customer')->assertOk()->assertSee('Chai Corner')->assertSee('1 more');
        $this->as($customer)->post(route('customer.offers.claim', $offer))
            ->assertRedirect(route('customer.claim-reward'))->assertSessionHas('error');

        // Third coin unlocks it; claiming spends the coins and creates a pending request.
        $this->scan($customer, $shop);
        $this->as($customer)->get('/customer/claim-reward')->assertOk()->assertSee('Free Chai')->assertSee('Claim Reward');
        $this->as($customer)->post(route('customer.offers.claim', $offer))->assertSessionHas('success');
        $claim = RewardRequest::where('offer_id', $offer->id)->firstOrFail();
        $this->assertSame(['pending', 3], [$claim->status, $claim->coins_spent]);
        $this->as($customer)->post(route('customer.offers.claim', $offer))->assertSessionHas('error'); // coins already spent

        // The merchant sees it with real numbers and approves it.
        $this->as($merchant)->get('/merchant')->assertOk()->assertSee('Chai Corner')->assertSee('waiting for approval');
        $this->as($merchant)->get('/merchant/rewards')->assertOk()->assertSee($claim->code);
        $this->as($merchant)->post("/merchant/rewards/{$claim->id}/status", ['status' => 'approved'])->assertSessionHas('success');
        $this->assertSame('approved', $claim->fresh()->status);
        $this->as($merchant)->post("/merchant/rewards/{$claim->id}/status", ['status' => 'declined'])->assertSessionHas('error');
        $this->assertSame('approved', $claim->fresh()->status);

        // Customer and admin see the redemption.
        $this->as($customer)->get('/customer/claim-reward?tab=history')->assertSee($claim->code)->assertSee('Approved');
        $admin = $this->user('admin', 'boss@example.com');
        $this->as($admin)->get('/admin/dashboard')->assertOk()->assertSee('Coins Collected')->assertSee('Rewards Redeemed');
    }

    public function test_declined_claim_returns_the_coins(): void
    {
        $merchant = $this->user('merchant', 'shop@example.com');
        $shop = Business::create(['user_id' => $merchant->id, 'name' => 'Chai Corner', 'email' => 'shop@example.com']);
        $offer = Offer::create(['business_id' => $shop->id, 'title' => 'Free Chai', 'orex_coins' => 1, 'expiry' => 30]);
        $customer = $this->user('customer', 'cust@example.com');

        $this->scan($customer, $shop);
        $this->as($customer)->post(route('customer.offers.claim', $offer))->assertSessionHas('success');
        $claim = RewardRequest::where('offer_id', $offer->id)->firstOrFail();
        $this->as($merchant)->post("/merchant/rewards/{$claim->id}/status", ['status' => 'declined']);

        // The coin is back, so the same reward can be claimed again.
        $this->as($customer)->post(route('customer.offers.claim', $offer))->assertSessionHas('success');
        $this->assertSame(2, RewardRequest::where('offer_id', $offer->id)->count());
    }

    public function test_merchants_cannot_touch_other_shops_claims(): void
    {
        $owner = $this->user('merchant', 'owner@example.com');
        $shop = Business::create(['user_id' => $owner->id, 'name' => 'Owner Shop', 'email' => 'owner@example.com']);
        $other = $this->user('merchant', 'other@example.com');
        Business::create(['user_id' => $other->id, 'name' => 'Other Shop', 'email' => 'other@example.com']);
        $claim = RewardRequest::create(['business_id' => $shop->id, 'customer_name' => 'X', 'reward_title' => 'Gift', 'code' => 'BX-TEST01', 'status' => 'pending', 'expires_at' => now()->addDay()]);

        $this->as($other)->post("/merchant/rewards/{$claim->id}/status", ['status' => 'approved'])->assertNotFound();
        $this->as($other)->get('/merchant/rewards')->assertDontSee('BX-TEST01');
        $this->assertSame('pending', $claim->fresh()->status);
    }

    public function test_blocked_customer_gets_no_coins(): void
    {
        $merchant = $this->user('merchant', 'shop@example.com');
        $shop = Business::create(['user_id' => $merchant->id, 'name' => 'Chai Corner', 'email' => 'shop@example.com']);
        $customer = $this->user('customer', 'cust@example.com');
        Customer::forUser($customer)->update(['status' => 'Blocked']);

        $this->as($customer)->get(route('customer.collect', $shop))->assertOk()->assertSee('Account restricted');
        $this->assertDatabaseCount('customer_visits', 0);
    }

    public function test_onboarding_pages_need_a_verified_merchant(): void
    {
        foreach (['/merchant/account-created', '/merchant/business-info', '/merchant/business-address', '/merchant/setup-complete'] as $url) {
            $this->get($url)->assertRedirect('/merchant/login');
        }
        $this->post('/merchant/business-info', ['business_name' => 'X', 'business_category' => 'Y'])->assertRedirect('/merchant/login');

        $customer = $this->user('customer', 'cust@example.com');
        $this->as($customer)->post('/merchant/business-info', ['business_name' => 'X', 'business_category' => 'Y'])->assertRedirect('/merchant/login');
        $this->assertDatabaseMissing('businesses', ['name' => 'X']);

        // A merchant still in set-up is sent back to their step instead of the dashboard.
        $midway = $this->user('merchant', 'mid@example.com', ['onboarding_step' => 'business_address']);
        $this->as($midway)->get('/merchant')->assertRedirect(route('merchant.business-address'));
        $this->as($midway)->get('/merchant/business-address')->assertOk();

        // A finished merchant is sent to the dashboard.
        $done = $this->user('merchant', 'done@example.com');
        $this->as($done)->get('/merchant/business-info')->assertRedirect(route('merchant.dashboard'));
    }

    public function test_many_customers_without_a_phone_can_log_in(): void
    {
        // customers.phone is unique: two customers without a phone used to crash the home page.
        foreach (['one@example.com', 'two@example.com', 'three@example.com'] as $email) {
            $this->as($this->user('customer', $email))->get('/customer')->assertOk();
        }
        $this->assertSame(3, Customer::whereNull('phone')->count());

        // Two customers giving the same phone number is not a crash either.
        $a = $this->user('customer', 'a@example.com', ['phone' => '+91 9876543210']);
        $b = $this->user('customer', 'b@example.com', ['phone' => '+91 9876543210']);
        $this->as($a)->get('/customer')->assertOk();
        $this->as($b)->get('/customer')->assertOk();
    }

    public function test_dashboards_show_no_made_up_numbers(): void
    {
        $merchant = $this->user('merchant', 'shop@example.com');
        Business::create(['user_id' => $merchant->id, 'name' => 'Quiet Shop', 'email' => 'shop@example.com']);
        $admin = $this->user('admin', 'boss@example.com');
        $customer = $this->user('customer', 'cust@example.com');

        $this->as($merchant)->get('/merchant')->assertDontSee('Ka-feen')->assertDontSee('+18.5%')->assertDontSee('20 Aug 2026');
        $this->as($admin)->get('/admin/dashboard')->assertDontSee('1,248')->assertDontSee('86,540')->assertDontSee('May 24, 2025');
        $this->as($customer)->get('/customer')->assertDontSee('Ka-feen')->assertDontSee('LQR-8F4A29')->assertSee('No coins yet');
    }
}
