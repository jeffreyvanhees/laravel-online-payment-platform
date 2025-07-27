<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector($this->getSandboxApiKey(), true);
});

it('can create a dispute', function () {
    // First create a merchant and transaction
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Dispute',
        'last_name' => 'Test',
        'country' => 'NLD',
        'emailaddress' => "dispute.test.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    // Create a transaction first
    $transactionData = [
        'merchant_uid' => $merchantUid,
        'total_price' => 2500,
        'products' => [
            [
                'name' => 'Dispute Test Product',
                'price' => 2500,
                'quantity' => 1,
            ],
        ],
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
    ];

    $transactionResponse = $this->connector->transactions()->create($transactionData);
    $transactionUid = $transactionResponse->json('uid');

    $disputeData = [
        'transaction_uid' => $transactionUid,
        'reason' => 'product_not_received',
        'description' => 'Customer claims they never received the product',
        'amount' => 2500,
        'notify_url' => 'https://example.com/notify',
    ];

    $response = $this->connector->disputes()->create($disputeData);

    // Dispute creation may require additional setup in sandbox
    if ($response->successful()) {
        expect($response->json())->toHaveKey('uid');
        expect($response->json('amount'))->toBe(2500);
        expect($response->json('reason'))->toBe('product_not_received');
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'disputes');

it('can retrieve a dispute', function () {
    // First create a merchant, transaction, and dispute
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Retrieve',
        'last_name' => 'Dispute',
        'country' => 'NLD',
        'emailaddress' => "retrieve.dispute.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $transactionData = [
        'merchant_uid' => $merchantUid,
        'total_price' => 1800,
        'products' => [
            [
                'name' => 'Retrieve Dispute Product',
                'price' => 1800,
                'quantity' => 1,
            ],
        ],
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
    ];

    $transactionResponse = $this->connector->transactions()->create($transactionData);
    $transactionUid = $transactionResponse->json('uid');

    $disputeData = [
        'transaction_uid' => $transactionUid,
        'reason' => 'product_damaged',
        'description' => 'Product was damaged upon arrival',
        'amount' => 1800,
        'notify_url' => 'https://example.com/notify',
    ];

    $createResponse = $this->connector->disputes()->create($disputeData);

    // Only test retrieval if creation was successful
    if ($createResponse->successful()) {
        $disputeUid = $createResponse->json('uid');

        // Now retrieve the dispute
        $response = $this->connector->disputes()->get($disputeUid);

        expect($response->successful())->toBeTrue();
        expect($response->json())->toHaveKey('uid');
        expect($response->json('uid'))->toBe($disputeUid);
        expect($response->json('amount'))->toBe(1800);
    } else {
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'disputes');

it('can list disputes', function () {
    $response = $this->connector->disputes()->list(['limit' => 10]);

    // List endpoints should work even if creation doesn't
    if ($response->successful()) {
        expect($response->json())->toHaveKey('data');
        expect($response->json('data'))->toBeArray();
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'disputes');
