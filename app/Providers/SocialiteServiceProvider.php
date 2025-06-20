<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Zalo\Provider as ZaloProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Socialite::extend('zalo', function ($app) {
        //     $config = $app['config']['services.zalo'];
        //     return Socialite::buildProvider(ZaloProvider::class, $config);
        // });
    }
}
