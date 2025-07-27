<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

it('initializes with sandbox environment by default', function () {
    $connector = new OnlinePaymentPlatformConnector('test-key');

    expect($connector->resolveBaseUrl())->toBe('https://api-sandbox.onlinebetaalplatform.nl/v1');
})->group('unit');

it('can use production environment', function () {
    $connector = new OnlinePaymentPlatformConnector('test-key', false);

    expect($connector->resolveBaseUrl())->toBe('https://api.onlinebetaalplatform.nl/v1');
})->group('unit');

it('provides access to merchants resource', function () {
    $connector = new OnlinePaymentPlatformConnector('test-key');

    expect($connector->merchants())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\MerchantsResource::class);
})->group('unit');

it('provides access to transactions resource', function () {
    $connector = new OnlinePaymentPlatformConnector('test-key');

    expect($connector->transactions())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\TransactionsResource::class);
})->group('unit');

it('provides access to charges resource', function () {
    $connector = new OnlinePaymentPlatformConnector('test-key');

    expect($connector->charges())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\ChargesResource::class);
})->group('unit');

it('provides access to mandates resource', function () {
    $connector = new OnlinePaymentPlatformConnector('test-key');

    expect($connector->mandates())->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Resources\MandatesResource::class);
})->group('unit');
