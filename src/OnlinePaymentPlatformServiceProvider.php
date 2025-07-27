<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform;

use Illuminate\Support\ServiceProvider;

class OnlinePaymentPlatformServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/opp.php',
            'opp'
        );

        $this->app->singleton(OnlinePaymentPlatformConnector::class, function ($app) {
            $config = $app['config']['opp'];

            return new OnlinePaymentPlatformConnector(
                $config['api_key'] ?? '',
                $config['sandbox'] ?? true
            );
        });

        $this->app->alias(OnlinePaymentPlatformConnector::class, 'opp');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/opp.php' => config_path('opp.php'),
        ], 'opp-config');

        $this->publishes([
            __DIR__.'/../config/opp.php' => config_path('opp.php'),
        ], 'opp');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<int, string>
     */
    public function provides(): array
    {
        return [
            OnlinePaymentPlatformConnector::class,
            'opp',
        ];
    }
}
