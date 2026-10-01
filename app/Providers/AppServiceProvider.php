<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // One password policy for every sign-up, reset and password change.
        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers()->symbols());

        // On the live site (APP_URL=https://…) every generated link must be https, even if the
        // proxy talks to PHP over http. Locally APP_URL is http, so nothing changes there.
        if (str_starts_with((string) config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }
    }
}
