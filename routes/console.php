<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:password {email} {--name=Admin User}', function (string $email) {
    $password = $this->secret('New password (min 8 characters)');
    if (strlen((string) $password) < 8) {
        return $this->error('Password must be at least 8 characters.');
    }

    $user = User::firstOrNew(['email' => $email, 'role' => 'admin']);
    $user->name ??= $this->option('name');
    $user->password = Hash::make($password);
    $user->email_verified_at ??= now();
    $user->save();

    $this->info(($user->wasRecentlyCreated ? 'Created' : 'Updated').' admin '.$email.'. Login at /admin');
})->purpose('Create an admin account or reset its password');
