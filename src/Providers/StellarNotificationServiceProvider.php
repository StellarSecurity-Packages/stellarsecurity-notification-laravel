<?php

namespace StellarSecurity\Notifications\Providers;

use Illuminate\Support\ServiceProvider;
use StellarSecurity\Notifications\StellarNotificationClient;

class StellarNotificationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/stellar-notifications.php', 'stellar-notifications');

        $this->app->singleton('stellar.notification', function () {
            return new StellarNotificationClient();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/stellar-notifications.php' => config_path('stellar-notifications.php'),
        ], 'stellar-notifications-config');
    }
}
