<?php

namespace Tests\Feature;

use App\Models\Deal;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ClaimCouponCodeTest extends TestCase
{
    use DatabaseTransactions;

    private function deal(string $code): array
    {
        // Every field the form posts, so the deals table can render the result.
        return array_fill_keys(['plan_name', 'plan_type', 'state', 'bonus_amount', 'discount_amount', 'discount_percentage', 'maximum_usage'], '')
            + ['deal_name' => 'New Year', 'coupon_code' => $code, 'validity_date' => now()->addMonth()->toDateString()];
    }

    public function test_coupon_code_accepts_capital_letters_only(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@beaurex.com', 'password' => 'password', 'role' => 'admin']);
        $this->actingAs($admin)->withSession(['admin_logged_in' => true]);

        foreach (['NEWYEAR2024', 'newyear', 'NewYear', 'NEW YEAR', 'NEW-YEAR', 'NEWYEAR!', 'ÉTÉ', ''] as $bad) {
            $this->from('/admin/claims')->post('/admin/claims', $this->deal($bad))->assertSessionHasErrors('coupon_code');
        }
        $this->assertSame(0, Deal::count());

        $this->post('/admin/claims', $this->deal('NEWYEAR'))->assertSessionHasNoErrors();
        $deal = Deal::sole();
        $this->assertSame('NEWYEAR', $deal->coupon_code);

        $this->post("/admin/claims/{$deal->id}", $this->deal('summer') + ['_method' => 'PUT'])->assertSessionHasErrors('coupon_code');
        $this->assertSame('NEWYEAR', $deal->fresh()->coupon_code);
        $this->get('/admin/claims')->assertOk()->assertSee('pattern="[A-Z]+"', false);
    }
}
