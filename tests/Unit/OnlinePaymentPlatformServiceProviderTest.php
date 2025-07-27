<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformServiceProvider;

describe('OnlinePaymentPlatformServiceProvider', function () {
    test('it extends Laravel ServiceProvider', function () {
        $app = Mockery::mock(\Illuminate\Contracts\Foundation\Application::class);
        $provider = new OnlinePaymentPlatformServiceProvider($app);

        expect($provider)->toBeInstanceOf(ServiceProvider::class);
    });

    test('it provides correct services', function () {
        $app = Mockery::mock(\Illuminate\Contracts\Foundation\Application::class);
        $provider = new OnlinePaymentPlatformServiceProvider($app);

        $services = $provider->provides();

        expect($services)->toEqual([
            OnlinePaymentPlatformConnector::class,
            'opp',
        ]);
    });

    test('it can create connector factory function', function () {
        // Test the factory function logic in isolation
        $config = [
            'api_key' => 'test_key',
            'sandbox' => true,
        ];

        $connector = new OnlinePaymentPlatformConnector(
            $config['api_key'] ?? '',
            $config['sandbox'] ?? true
        );

        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });

    test('it handles empty config gracefully', function () {
        $config = [];

        $connector = new OnlinePaymentPlatformConnector(
            $config['api_key'] ?? '',
            $config['sandbox'] ?? true
        );

        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });

    test('it handles partial config with api key only', function () {
        $config = [
            'api_key' => 'partial_key',
        ];

        $connector = new OnlinePaymentPlatformConnector(
            $config['api_key'] ?? '',
            $config['sandbox'] ?? true
        );

        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });

    test('it handles production configuration', function () {
        $config = [
            'api_key' => 'prod_key',
            'sandbox' => false,
        ];

        $connector = new OnlinePaymentPlatformConnector(
            $config['api_key'] ?? '',
            $config['sandbox'] ?? true
        );

        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });
});
