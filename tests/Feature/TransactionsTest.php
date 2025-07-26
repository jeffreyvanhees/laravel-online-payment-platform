<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use Spatie\LaravelData\DataCollection;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector($this->getSandboxApiKey(), true);
});

it('can create a transaction', function () {
    // First create a merchant with unique email
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'country' => 'NLD',
        'emailaddress' => "john.doe.transaction.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $transactionData = [
        'merchant_uid' => $merchantUid,
        'total_price' => 1000, // €10.00 in cents
        'products' => [
            [
                'name' => 'Test Product',
                'price' => 1000,
                'quantity' => 1,
            ],
        ],
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
    ];

    $response = $this->connector->transactions()->create($transactionData);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('amount'))->toBe(1000);
})->group('recording', 'replay', 'transactions');

it('can create a transaction using DTO', function () {
    // First create a merchant with unique email
    $timestamp = time() + 1; // Add 1 to ensure different timestamp
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Jane',
        'last_name' => 'Doe',
        'country' => 'NLD',
        'emailaddress' => "jane.doe.transaction.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $transactionData = new CreateTransactionData(
        merchant_uid: $merchantUid,
        total_price: 1000,
        return_url: 'https://example.com/return',
        notify_url: 'https://example.com/notify',
        products: ProductData::collect([
            [
                'name' => 'Test Product DTO',
                'quantity' => 1,
                'price' => 1000,
            ],
        ]),
    );

    $response = $this->connector->transactions()->create($transactionData);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('amount'))->toBe(1000);
    
    // Test DTO creation from response
    $transactionDto = $response->dto();
    expect($transactionDto)->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\TransactionData::class);
    expect($transactionDto->amount)->toBe(1000);
    expect($transactionDto->merchant_uid)->toBe($merchantUid);
})->group('recording', 'replay', 'transactions');

it('can retrieve a transaction', function () {
    // First create a merchant and transaction
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Retrieve',
        'last_name' => 'Transaction',
        'country' => 'NLD',
        'emailaddress' => "retrieve.transaction.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $transactionData = [
        'merchant_uid' => $merchantUid,
        'total_price' => 1500,
        'products' => [
            [
                'name' => 'Retrieve Test Product',
                'price' => 1500,
                'quantity' => 1,
            ],
        ],
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
    ];

    $createResponse = $this->connector->transactions()->create($transactionData);
    $transactionUid = $createResponse->json('uid');
    
    // Now retrieve the transaction
    $response = $this->connector->transactions()->get($transactionUid);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('uid'))->toBe($transactionUid);
})->group('recording', 'replay', 'transactions');

it('can list transactions', function () {
    $response = $this->connector->transactions()->list(['limit' => 10]);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('data');
    expect($response->json('data'))->toBeArray();
})->group('recording', 'replay', 'transactions');

it('can update a transaction', function () {
    // First create a merchant and transaction
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Update',
        'last_name' => 'Transaction',
        'country' => 'NLD',
        'emailaddress' => "update.transaction.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $merchantResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $merchantResponse->json('uid');

    $transactionData = [
        'merchant_uid' => $merchantUid,
        'total_price' => 2000,
        'products' => [
            [
                'name' => 'Update Test Product',
                'price' => 2000,
                'quantity' => 1,
            ],
        ],
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
    ];

    $createResponse = $this->connector->transactions()->create($transactionData);
    $transactionUid = $createResponse->json('uid');

    $updateData = [
        'description' => 'Updated transaction description',
    ];

    $response = $this->connector->transactions()->update($transactionUid, $updateData);

    expect($response->successful())->toBeTrue();
})->group('recording', 'replay', 'transactions');