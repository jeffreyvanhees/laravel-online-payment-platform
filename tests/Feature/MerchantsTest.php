<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateBusinessMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use Saloon\Lawman\Contracts\RecordsRequests;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector($this->getSandboxApiKey(), true);
});

it('can create a merchant', function () {
    $randomEmail = 'test_' . uniqid() . '_' . time() . '@example.com';
    
    $merchantData = [
        'type' => 'consumer',
        'country' => 'NLD',
        'emailaddress' => $randomEmail,
        'first_name' => 'John',
        'last_name' => 'Doe',
        'notify_url' => 'https://example.com/webhook',
    ];

    $response = $this->connector->merchants()->create($merchantData);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('type'))->toBe('consumer');
    expect($response->json('notify_url'))->toBe('https://example.com/webhook');
})->group('recording', 'replay', 'merchants');

it('can create a merchant using DTO', function () {
    $randomEmail = 'test_dto_' . uniqid() . '_' . time() . '@example.com';
    
    $merchantData = new CreateConsumerMerchantData(
        type: 'consumer',
        country: 'NLD',
        emailaddress: $randomEmail,
        first_name: 'Jane',
        last_name: 'Smith',
        notify_url: 'https://example.com/webhook'
    );

    $response = $this->connector->merchants()->create($merchantData);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('type'))->toBe('consumer');
    expect($response->json('notify_url'))->toBe('https://example.com/webhook');
    
    // Test DTO creation from response
    $merchantDto = $response->dto();
    expect($merchantDto)->toBeInstanceOf(\JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData::class);
    expect($merchantDto->type)->toBe('consumer');
    expect($merchantDto->notify_url)->toBe('https://example.com/webhook');
})->group('recording', 'replay', 'merchants');

it('can retrieve a merchant', function () {
    // First create a merchant
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Retrieve',
        'last_name' => 'Test',
        'country' => 'NLD',
        'emailaddress' => "retrieve.test.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $createResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $createResponse->json('uid');
    
    // Now retrieve the merchant
    $response = $this->connector->merchants()->get($merchantUid);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('uid'))->toBe($merchantUid);
})->group('recording', 'replay', 'merchants');

it('can list merchants', function () {
    $response = $this->connector->merchants()->list(['limit' => 10]);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('data');
    expect($response->json('data'))->toBeArray();
})->group('recording', 'replay', 'merchants');

it('can add a contact to a merchant', function () {
    // First create a merchant
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Contact',
        'last_name' => 'Test',
        'country' => 'NLD',
        'emailaddress' => "contact.test.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $createResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $createResponse->json('uid');

    $contactData = [
        'type' => 'representative',
        'gender' => 'm',
        'title' => 'mr',
        'name' => [
            'first' => 'Jane',
            'last' => 'Smith',
            'initials' => 'J.S.',
            'names_given' => 'Jane',
        ],
        'emailaddresses' => [
            [
                'emailaddress' => "jane.smith.{$timestamp}@example.com",
            ]
        ],
        'phonenumbers' => [
            [
                'phonenumber' => '+31612345678',
            ]
        ],
    ];

    $response = $this->connector->merchants()->contacts($merchantUid)->add($contactData);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
})->group('recording', 'replay', 'merchants');

it('can add an address to a merchant', function () {
    // First create a merchant
    $timestamp = time();
    $merchantData = [
        'type' => 'consumer',
        'first_name' => 'Address',
        'last_name' => 'Test',
        'country' => 'NLD',
        'emailaddress' => "address.test.{$timestamp}@example.com",
        'notify_url' => 'https://example.com/notify',
    ];

    $createResponse = $this->connector->merchants()->create($merchantData);
    $merchantUid = $createResponse->json('uid');

    $addressData = [
        'type' => 'business',
        'address_line_1' => 'Test Street 123',
        'city' => 'Amsterdam',
        'zipcode' => '1000 AA',
        'country' => 'NLD',
    ];

    $response = $this->connector->merchants()->addresses($merchantUid)->add($addressData);

    expect($response->successful())->toBeTrue();
    expect($response->json())->toHaveKey('uid');
    expect($response->json('city'))->toBe('Amsterdam');
})->group('recording', 'replay', 'merchants');