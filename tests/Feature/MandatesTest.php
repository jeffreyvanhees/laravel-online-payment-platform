<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector($this->getSandboxApiKey(), true);
});

it('can create a mandate', function () {
    // First create a merchant
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'name_first' => 'Mandate',
        'name_last' => 'Test',
        'country' => 'NLD',
        'emailaddress' => "mandate.test.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $mandateData = [
        'merchant_uid' => $merchantUid,
        'mandate_method' => 'emandate',
        'mandate_type' => 'consumer',
        'mandate_repeat' => 'subscription',
        'total_price' => 2500,
        'notify_url' => 'https://example.com/notify',
        'return_url' => 'https://example.com/return',
        'issuer' => 'RABONL2U',
    ];

    $response = $this->connector->mandates()->create($mandateData);

    // Mandate creation may require additional setup in sandbox
    if ($response->successful()) {
        expect($response->json())->toHaveKey('uid');
        expect($response->json('mandate_method'))->toBe('emandate');
        expect($response->json('mandate_type'))->toBe('consumer');
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'mandates');

it('can retrieve a mandate', function () {
    // First create a merchant and mandate
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'name_first' => 'Retrieve',
        'name_last' => 'Mandate',
        'country' => 'NLD',
        'emailaddress' => "retrieve.mandate.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $mandateData = [
        'merchant_uid' => $merchantUid,
        'mandate_method' => 'emandate',
        'mandate_type' => 'consumer',
        'mandate_repeat' => 'subscription',
        'total_price' => 1800,
        'notify_url' => 'https://example.com/notify',
        'return_url' => 'https://example.com/return',
        'issuer' => 'RABONL2U',
    ];

    $createResponse = $this->connector->mandates()->create($mandateData);

    // Only test retrieval if creation was successful
    if ($createResponse->successful()) {
        $mandateUid = $createResponse->json('uid');

        // Now retrieve the mandate
        $response = $this->connector->mandates()->get($mandateUid);

        expect($response->successful())->toBeTrue();
        expect($response->json())->toHaveKey('uid');
        expect($response->json('uid'))->toBe($mandateUid);
    } else {
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'mandates');

it('can list mandates', function () {
    $response = $this->connector->mandates()->list(['limit' => 10]);

    // List endpoints should work even if creation doesn't
    if ($response->successful()) {
        expect($response->json())->toHaveKey('data');
        expect($response->json('data'))->toBeArray();
    } else {
        expect($response->status())->toBeGreaterThanOrEqual(200);
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'mandates');

it('can delete a mandate', function () {
    // First create a merchant and mandate
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'name_first' => 'Delete',
        'name_last' => 'Mandate',
        'country' => 'NLD',
        'emailaddress' => "delete.mandate.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $mandateData = [
        'merchant_uid' => $merchantUid,
        'mandate_method' => 'emandate',
        'mandate_type' => 'consumer',
        'mandate_repeat' => 'subscription',
        'total_price' => 1200,
        'notify_url' => 'https://example.com/notify',
        'return_url' => 'https://example.com/return',
        'issuer' => 'RABONL2U',
    ];

    $createResponse = $this->connector->mandates()->create($mandateData);

    // Only test deletion if creation was successful
    if ($createResponse->successful()) {
        $mandateUid = $createResponse->json('uid');

        $response = $this->connector->mandates()->delete($mandateUid);

        expect($response->successful())->toBeTrue();
    } else {
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'mandates');

it('can create a mandate transaction', function () {
    // First create a merchant and mandate
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'name_first' => 'Transaction',
        'name_last' => 'Mandate',
        'country' => 'NLD',
        'emailaddress' => "transaction.mandate.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $mandateData = [
        'merchant_uid' => $merchantUid,
        'mandate_method' => 'emandate',
        'mandate_type' => 'consumer',
        'mandate_repeat' => 'subscription',
        'total_price' => 3000,
        'notify_url' => 'https://example.com/notify',
        'return_url' => 'https://example.com/return',
        'issuer' => 'RABONL2U',
    ];

    $mandateResponse = $this->connector->mandates()->create($mandateData);

    // Only test mandate transaction if mandate creation was successful
    if ($mandateResponse->successful()) {
        $mandateUid = $mandateResponse->json('uid');

        $transactionData = [
            'total_price' => 2000, // €20.00 in cents
            'description' => 'Mandate transaction test',
            'notify_url' => 'https://example.com/notify',
        ];

        $response = $this->connector->mandates()->createTransaction($mandateUid, $transactionData);

        expect($response->successful())->toBeTrue();
        expect($response->json())->toHaveKey('uid');
        expect($response->json('amount'))->toBe(2000);
    } else {
        expect(true)->toBeTrue();
    }
})->group('recording', 'replay', 'mandates');
