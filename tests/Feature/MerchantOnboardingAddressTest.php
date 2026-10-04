<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Merchant sign-up steps 5-6: business logo upload and the India Post State → District → PIN → Area address.
 */
class MerchantOnboardingAddressTest extends TestCase
{
    use DatabaseTransactions;

    private function merchant(string $step): User
    {
        $user = User::create(['name' => 'Shop Owner', 'email' => 'owner@example.com', 'password' => Hash::make('Pass@1234'), 'role' => 'merchant', 'phone' => '+91 9876543210']);
        $user->forceFill(['email_verified_at' => now(), 'onboarding_step' => $step])->save();

        return $user;
    }

    private function as(User $user): static
    {
        return $this->actingAs($user)->withSession(['merchant_logged_in' => true]);
    }

    public function test_location_lookups_cover_all_of_india(): void
    {
        $this->get('/locations/districts?state=Delhi')->assertOk()->assertJsonFragment(['New Delhi']);
        $this->get('/locations/districts?state=Ladakh')->assertOk()->assertJsonFragment(['Leh']);
        $this->get('/locations/pincodes?state=Haryana&district=Faridabad')->assertOk()->assertJsonFragment(['pincode' => '121001']);
        $this->get('/locations/pincode/121001')->assertOk()
            ->assertJson(['state' => 'Haryana', 'district' => 'Faridabad'])
            ->assertJsonFragment(['Faridabad NIT']);
        $this->get('/locations/pincode/999999')->assertNotFound();
        $this->get('/locations/districts?state=Atlantis')->assertNotFound();

        $merchant = $this->merchant('business_address');
        Business::create(['user_id' => $merchant->id, 'name' => 'Chai Corner', 'email' => 'owner@example.com']);
        $page = $this->as($merchant)->get('/merchant/business-address')->assertOk();
        foreach (['Andhra Pradesh', 'Ladakh', 'Puducherry', 'Dadra and Nagar Haveli and Daman and Diu', 'Telangana', 'West Bengal'] as $state) {
            $page->assertSee($state);
        }
    }

    public function test_address_must_use_a_matching_state_district_and_pin(): void
    {
        $merchant = $this->merchant('business_address');
        $shop = Business::create(['user_id' => $merchant->id, 'name' => 'Chai Corner', 'email' => 'owner@example.com']);
        $address = ['address_line_1' => 'Shop 4, Main Market', 'state' => 'Haryana', 'city' => 'Faridabad', 'pin_code' => '121001', 'area' => 'Faridabad NIT'];

        $this->as($merchant)->post('/merchant/business-address', ['pin_code' => '110001'] + $address)->assertSessionHasErrors('pin_code');
        $this->as($merchant)->post('/merchant/business-address', ['city' => 'Gurugram Nowhere'] + $address)->assertSessionHasErrors('city');
        $this->as($merchant)->post('/merchant/business-address', ['state' => 'Atlantis'] + $address)->assertSessionHasErrors('state');
        $this->as($merchant)->post('/merchant/business-address', ['pin_code' => '12100'] + $address)->assertSessionHasErrors('pin_code');

        $this->as($merchant)->post('/merchant/business-address', $address)->assertRedirect(route('merchant.setup-complete'));
        $shop->refresh();
        $this->assertSame(['Haryana', 'Faridabad', '121001'], [$shop->state, $shop->city, $shop->pincode]);
        $this->assertSame('Shop 4, Main Market, Faridabad NIT, Faridabad, Haryana - 121001', $shop->address);
    }

    public function test_business_info_saves_the_logo_and_rejects_unsafe_files(): void
    {
        $merchant = $this->merchant('business_information');

        $this->as($merchant)->post('/merchant/business-info', [
            'business_name' => 'Chai Corner', 'business_category' => 'Cafe',
            'logo' => UploadedFile::fake()->create('logo.svg', 10, 'image/svg+xml'),
        ])->assertSessionHasErrors('logo');
        $this->as($merchant)->post('/merchant/business-info', ['business_name' => 'Chai Corner', 'business_category' => 'Spaceship'])
            ->assertSessionHasErrors('business_category');

        $this->as($merchant)->post('/merchant/business-info', [
            'business_name' => 'Chai Corner', 'business_category' => 'Cafe',
            'logo' => UploadedFile::fake()->image('logo.png', 300, 300),
        ])->assertRedirect(route('merchant.business-address'));

        $shop = Business::where('user_id', $merchant->id)->firstOrFail();
        $this->assertStringStartsWith('uploads/merchant_logos/', $shop->logo);
        $this->assertFileExists(public_path($shop->logo));
        File::delete(public_path($shop->logo));

        $this->as($merchant->fresh())->get('/merchant/business-info')->assertOk()->assertSee('Chai Corner');
    }
}
