<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\User;
use App\Support\Media;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Uploads must be visible on the live host, which refuses to serve the public/storage symlink.
 */
class MediaUploadTest extends TestCase
{
    use DatabaseTransactions;

    private array $created = [];

    protected function tearDown(): void
    {
        foreach ($this->created as $path) {
            File::delete(public_path($path));
        }
        parent::tearDown();
    }

    private function merchant(): array
    {
        $user = User::create(['name' => 'Shop Owner', 'email' => 'owner@example.com', 'password' => Hash::make('Pass@1234'), 'role' => 'merchant', 'phone' => '+91 9876543210']);
        $user->forceFill(['email_verified_at' => now(), 'onboarding_step' => 'completed'])->save();
        $shop = Business::create(['user_id' => $user->id, 'name' => 'Chai Corner', 'email' => 'owner@example.com', 'logo' => 'merchant_logos/old.png']);

        return [$user, $shop];
    }

    public function test_merchant_profile_saves_and_shows_the_new_logo_from_public_uploads(): void
    {
        [$user, $shop] = $this->merchant();

        $this->actingAs($user)->withSession(['merchant_logged_in' => true])
            ->post('/merchant/profile', [
                'business_name' => 'Chai Corner Cafe', 'name' => 'Shop Owner', 'phone' => '9876543210',
                'email' => 'owner@example.com', 'logo' => UploadedFile::fake()->image('logo.png', 200, 200),
            ])->assertSessionHasNoErrors()->assertSessionHas('success');

        $shop->refresh();
        $this->created[] = $shop->logo;
        $this->assertSame('Chai Corner Cafe', $shop->name);
        $this->assertStringStartsWith('uploads/merchant_logos/', $shop->logo);
        $this->assertFileExists(public_path($shop->logo));

        $this->actingAs($user)->withSession(['merchant_logged_in' => true])
            ->get('/merchant/profile')->assertOk()->assertSee(asset($shop->logo), false);
    }

    public function test_profile_photo_goes_to_public_uploads(): void
    {
        $customer = User::create(['name' => 'Asha Rao', 'email' => 'asha@example.com', 'password' => Hash::make('Pass@1234'), 'role' => 'customer']);
        $customer->forceFill(['email_verified_at' => now()])->save();

        $this->actingAs($customer)->withSession(['customer_logged_in' => true])
            ->post('/customer/profile', ['name' => 'Asha Rao', 'photo' => UploadedFile::fake()->image('me.jpg')])
            ->assertSessionHasNoErrors();

        $photo = $customer->fresh()->photo;
        $this->created[] = $photo;
        $this->assertStringStartsWith('uploads/profiles/', $photo);
        $this->assertFileExists(public_path($photo));
    }

    public function test_older_uploads_are_served_without_the_storage_symlink(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('merchant_logos/old.png', 'png-bytes');

        $this->assertSame(url('media/merchant_logos/old.png'), Media::url('merchant_logos/old.png'));
        $this->assertSame(url('media/profiles/me.jpg'), Media::url('/storage/profiles/me.jpg'));
        $this->assertSame('https://example.com/x.png', Media::url('https://example.com/x.png'));
        $this->assertNull(Media::url(null));

        $this->get('/media/merchant_logos/old.png')->assertOk();
        $this->get('/media/merchant_logos/missing.png')->assertNotFound();
        $this->get('/media/../.env')->assertNotFound();
        $this->get('/media/secret/file.txt')->assertNotFound();
    }
}
