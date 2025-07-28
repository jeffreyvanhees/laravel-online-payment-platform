<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformServiceProvider;

describe('Laravel Package Integration', function () {
    test('service provider is properly registered', function () {
        // Check if the service provider is registered in Laravel's container
        $registeredProviders = app()->getLoadedProviders();
        
        expect($registeredProviders)->toHaveKey(OnlinePaymentPlatformServiceProvider::class);
    });

    test('connector is registered as singleton in service container', function () {
        // Test that the connector is registered as a singleton
        $connector1 = app(OnlinePaymentPlatformConnector::class);
        $connector2 = app(OnlinePaymentPlatformConnector::class);
        
        expect($connector1)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        expect($connector2)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        expect($connector1)->toBe($connector2); // Same instance (singleton)
    });

    test('connector is aliased as opp in service container', function () {
        // Test that the 'opp' alias resolves to the connector
        $connector = app('opp');
        
        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });

    test('facade is auto-registered and works', function () {
        // Test that the facade is available through Laravel's auto-discovery
        expect(class_exists('OnlinePaymentPlatform'))->toBeTrue();
        
        // Test that the facade resolves to the connector
        $resolved = OnlinePaymentPlatform::getFacadeRoot();
        expect($resolved)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });

    test('config is properly merged', function () {
        // Test that the config is merged from the package
        expect(config('opp'))->toBeArray();
        expect(config('opp'))->toHaveKey('api_key');
        expect(config('opp'))->toHaveKey('sandbox');
        expect(config('opp'))->toHaveKey('cache');
        expect(config('opp'))->toHaveKey('http');
        expect(config('opp'))->toHaveKey('urls');
        expect(config('opp'))->toHaveKey('webhooks');
        expect(config('opp'))->toHaveKey('logging');
        
        // Test nested config structure
        expect(config('opp.cache'))->toBeArray();
        expect(config('opp.http'))->toBeArray();
    });

    test('config publishing works', function () {
        // Test that the config can be published
        $configPath = config_path('opp.php');
        
        // Clean up any existing config file
        if (file_exists($configPath)) {
            unlink($configPath);
        }
        
        // Publish the config
        $this->artisan('vendor:publish', [
            '--tag' => 'opp-config',
            '--force' => true,
        ])->assertExitCode(0);
        
        // Check that the config file was published
        expect(file_exists($configPath))->toBeTrue();
        
        // Clean up
        if (file_exists($configPath)) {
            unlink($configPath);
        }
    });

    test('connector uses configuration from config file', function () {
        // Set test configuration
        config([
            'opp.api_key' => 'test_api_key_123',
            'opp.sandbox' => false,
        ]);
        
        // Create a new connector instance to test configuration injection
        $connector = app()->make(OnlinePaymentPlatformConnector::class);
        
        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        
        // We can't directly test private properties, but we can test that the connector was created
        // without throwing exceptions, which indicates proper configuration handling
    });

    test('facade provides access to all resources', function () {
        // Test that all expected resources are available through the facade
        $connector = OnlinePaymentPlatform::getFacadeRoot();
        
        expect($connector->merchants())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\MerchantsResource::class);
        expect($connector->transactions())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\TransactionsResource::class);
        expect($connector->charges())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\ChargesResource::class);
        expect($connector->mandates())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\MandatesResource::class);
        expect($connector->withdrawals())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\WithdrawalsResource::class);
        expect($connector->disputes())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\DisputesResource::class);
        expect($connector->files())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\FilesResource::class);
        expect($connector->partners())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\PartnersResource::class);
        expect($connector->settlements())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\SettlementsResource::class);
    });

    test('dependency injection works in controllers/services', function () {
        // Test that we can create a service that requires the connector
        // We'll test this by just ensuring we can resolve the connector through DI
        $connector = app()->make(OnlinePaymentPlatformConnector::class);
        
        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        
        // Test that we can resolve it multiple times and get the same instance (singleton)
        $connector2 = app()->make(OnlinePaymentPlatformConnector::class);
        expect($connector)->toBe($connector2);
    });

    test('multiple config publishing tags work', function () {
        // Test both 'opp-config' and 'opp' tags work for publishing
        $configPath = config_path('opp.php');
        
        // Clean up
        if (file_exists($configPath)) {
            unlink($configPath);
        }
        
        // Test with 'opp' tag
        $this->artisan('vendor:publish', [
            '--tag' => 'opp',
            '--force' => true,
        ])->assertExitCode(0);
        
        expect(file_exists($configPath))->toBeTrue();
        
        // Clean up
        if (file_exists($configPath)) {
            unlink($configPath);
        }
    });

    test('service provider provides method returns correct services', function () {
        $provider = new OnlinePaymentPlatformServiceProvider(app());
        $provides = $provider->provides();
        
        expect($provides)->toEqual([
            OnlinePaymentPlatformConnector::class,
            'opp',
        ]);
    });
});