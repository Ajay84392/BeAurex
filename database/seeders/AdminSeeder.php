<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * The single admin account: admin@beaurex.com (ADMIN_EMAIL) with the password "password".
 * Change that password on any public server (php artisan admin:password admin@beaurex.com).
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => config('auth.admin_email'), 'role' => 'admin']);
        $admin->name ??= 'Admin';
        $admin->password = Hash::make('password');
        $admin->email_verified_at ??= now();
        $admin->save();
    }
}
