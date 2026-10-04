<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PlanColorsTest extends TestCase
{
    use DatabaseTransactions;

    private function plan(array $overrides = []): array
    {
        // Exactly what the plan form posts (it has no button fields).
        return $overrides + [
            'name' => 'Growth Plan', 'price' => 999, 'billing_cycle' => 'Monthly', 'type' => 'Standard', 'is_active' => 1,
            'short_description' => 'Best for cafes', 'features' => ['Unlimited scans'],
        ];
    }

    public function test_admin_sets_plan_colours_and_the_home_page_uses_them(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@beaurex.com', 'password' => 'password', 'role' => 'admin']);
        $this->actingAs($admin)->withSession(['admin_logged_in' => true]);

        $this->get('/admin/plans/create')->assertOk()->assertSee('Plan Text Colour')->assertSee('Plan Background Colour');

        foreach (['red', '#fff', '#12345g', 'url(x)'] as $bad) {
            $this->post('/admin/plans', $this->plan(['text_color' => $bad]))->assertSessionHasErrors('text_color');
        }

        $this->post('/admin/plans', $this->plan(['text_color' => '#1E40AF', 'bg_color' => '#FEF3C7']))->assertSessionHasNoErrors();
        $plan = Plan::where('name', 'Growth Plan')->sole();
        $this->assertSame(['#1E40AF', '#FEF3C7'], [$plan->text_color, $plan->bg_color]);
        $this->assertSame('Start 2-Day Trial', $plan->button_text); // column default, since the form has no button fields

        $this->get('/')->assertOk()->assertSee('style="background-color:#FEF3C7;--plan-text:#1E40AF" data-plan-text', false);
        $this->get("/admin/plans/{$plan->id}/edit")->assertOk()->assertSee('value="#1E40AF"', false);

        // "Default" sends an empty value: the card goes back to its standard look.
        $this->put("/admin/plans/{$plan->id}", $this->plan(['text_color' => '', 'bg_color' => '']))->assertSessionHasNoErrors();
        $this->assertNull($plan->fresh()->text_color);
        $this->assertNull($plan->fresh()->bg_color);
        $this->get('/')->assertOk()->assertDontSee('--plan-text:', false);
    }
}
