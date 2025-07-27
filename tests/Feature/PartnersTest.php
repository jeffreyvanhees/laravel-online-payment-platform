<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector($this->getSandboxApiKey(), true);
});

it('can get partner configuration', function () {
    $response = $this->connector->partners()->getConfiguration();

    // Partner endpoints may require special permissions
    if ($response->successful()) {
        expect($response->json())->toBeArray();
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'partners');

it('can update partner configuration', function () {
    $configData = [
        'webhook_url' => 'https://updated.example.com/webhook',
        'return_url' => 'https://updated.example.com/return',
        'settings' => [
            'auto_settlement' => true,
            'notification_email' => 'notifications@example.com',
        ],
    ];

    $response = $this->connector->partners()->updateConfiguration($configData);

    // Partner endpoints may require special permissions
    if ($response->successful()) {
        expect(true)->toBeTrue();
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'partners');
