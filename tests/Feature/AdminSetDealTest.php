<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminSetDealTest extends TestCase
{
    use DatabaseTransactions;

    private function shop(string $email, string $name): Business
    {
        $owner = User::create(['name' => $name, 'email' => $email, 'password' => 'Pass@1234', 'role' => 'merchant']);

        return Business::create(['user_id' => $owner->id, 'name' => $name, 'email' => $email]);
    }

    private function deal(string $name, string $code): Deal
    {
        return Deal::create(['deal_name' => $name, 'coupon_code' => $code, 'validity_date' => now()->addMonth()->toDateString()]);
    }

    public function test_admin_sets_a_deal_from_the_saved_deals(): void
    {
        $chai = $this->shop('chai@example.com', 'Chai Corner');
        $this->shop('cafe@example.com', 'Ka-feen Cafe');

        $admin = User::create(['name' => 'Admin', 'email' => 'admin@beaurex.com', 'password' => 'password', 'role' => 'admin']);
        $this->actingAs($admin)->withSession(['admin_logged_in' => true]);

        // No deals saved yet: every row still offers "No Deal".
        $this->get('/admin/merchants')->assertOk()->assertSee('No Deal');

        // A deal saved on Manage Deals shows up in every row's dropdown, newest first.
        $this->post('/admin/claims', ['deal_name' => 'New Year', 'coupon_code' => 'NEWYEAR', 'validity_date' => now()->addMonth()->toDateString()])->assertSessionHasNoErrors();
        $newYear = Deal::where('coupon_code', 'NEWYEAR')->firstOrFail();
        $summer = $this->deal('Summer Sale', 'SUMMER');
        $this->get('/admin/merchants')->assertOk()
            ->assertSeeInOrder(['Chai Corner', 'No Deal', 'Summer Sale (SUMMER)', 'New Year (NEWYEAR)'])
            ->assertSeeInOrder(['Ka-feen Cafe', 'No Deal', 'Summer Sale (SUMMER)', 'New Year (NEWYEAR)']);

        $this->patchJson("/admin/merchants/{$chai->id}/deal", ['deal_id' => $newYear->id])->assertOk()->assertJson(['success' => true]);
        $this->assertSame($newYear->id, $chai->fresh()->deal_id);
        $this->get('/admin/merchants')->assertSee("rowDeal: '{$newYear->id}'", false);

        // An unknown deal is refused; "No Deal" clears it.
        $this->patchJson("/admin/merchants/{$chai->id}/deal", ['deal_id' => 999999])->assertUnprocessable();
        $this->assertSame($newYear->id, $chai->fresh()->deal_id);
        $this->patchJson("/admin/merchants/{$chai->id}/deal", ['deal_id' => null])->assertOk();
        $this->assertNull($chai->fresh()->deal_id);

        // Deleting the deal on Manage Deals clears it instead of leaving a broken link.
        $this->patchJson("/admin/merchants/{$chai->id}/deal", ['deal_id' => $summer->id])->assertOk();
        $this->delete("/admin/claims/{$summer->id}");
        $this->assertNull($chai->fresh()->deal_id);
    }

    public function test_only_admins_can_set_deals(): void
    {
        $shop = $this->shop('chai@example.com', 'Chai Corner');
        $deal = $this->deal('New Year', 'NEWYEAR');

        $this->patchJson("/admin/merchants/{$shop->id}/deal", ['deal_id' => $deal->id])->assertRedirect('/admin');
        $this->assertNull($shop->fresh()->deal_id);
    }
}
