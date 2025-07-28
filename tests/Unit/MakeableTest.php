<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

describe('Makeable Trait', function () {
    test('can create connector using make method', function () {
        $connector = OnlinePaymentPlatformConnector::make('test-api-key');
        
        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        expect($connector->resolveBaseUrl())->toContain('onlinebetaalplatform.nl');
    });

    test('make method defaults to sandbox environment', function () {
        $connector = OnlinePaymentPlatformConnector::make('test-api-key');
        
        expect($connector->resolveBaseUrl())->toContain('sandbox');
    });

    test('make method can explicitly set production environment', function () {
        $connector = OnlinePaymentPlatformConnector::make('test-api-key', false);
        
        expect($connector->resolveBaseUrl())->not->toContain('sandbox');
        expect($connector->resolveBaseUrl())->toContain('api.onlinebetaalplatform.nl');
    });

    test('can create connector with sandbox flag using make method', function () {
        $connector = OnlinePaymentPlatformConnector::make('test-api-key', true);
        
        expect($connector)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        expect($connector->resolveBaseUrl())->toContain('sandbox');
    });

    test('make method creates same instance as constructor', function () {
        $apiKey = 'test-api-key';
        $sandbox = true;
        
        $connectorMake = OnlinePaymentPlatformConnector::make($apiKey, $sandbox);
        $connectorNew = new OnlinePaymentPlatformConnector($apiKey, $sandbox);
        
        expect($connectorMake->resolveBaseUrl())->toBe($connectorNew->resolveBaseUrl());
        expect($connectorMake)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
        expect($connectorNew)->toBeInstanceOf(OnlinePaymentPlatformConnector::class);
    });
});