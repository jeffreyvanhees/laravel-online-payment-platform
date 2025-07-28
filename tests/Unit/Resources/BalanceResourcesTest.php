<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantProfileBalanceRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners\GetPartnerMerchantBalanceRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\ProfilesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\PartnersResource;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('Balance Resources', function () {
    describe('ProfilesResource balance method', function () {
        test('balance method returns correct response', function () {
            $connector = new OnlinePaymentPlatformConnector('test-key', true);
            $merchantUid = 'mer_123456789';
            $profileUid = 'pro_987654321';

            $mockClient = new MockClient([
                GetMerchantProfileBalanceRequest::class => MockResponse::make([
                    'object' => 'balance',
                    'available' => 1000.5,
                    'pending' => 250.75,
                    'currency' => 'EUR',
                    'last_updated' => '2024-01-15T10:30:00Z',
                ]),
            ]);

            $connector->withMockClient($mockClient);
            $resource = new ProfilesResource($connector, $merchantUid);

            $response = $resource->balance($profileUid);

            expect($response->successful())->toBeTrue();
            expect($response->json('object'))->toBe('balance');
            expect($response->json('available'))->toBe(1000.5);
            expect($response->json('pending'))->toBe(250.75);
            expect($response->json('currency'))->toBe('EUR');
        });

        test('balance method with parameters', function () {
            $connector = new OnlinePaymentPlatformConnector('test-key', true);
            $merchantUid = 'mer_123456789';
            $profileUid = 'pro_987654321';

            $mockClient = new MockClient([
                GetMerchantProfileBalanceRequest::class => MockResponse::make([
                    'object' => 'balance',
                    'available' => 500.25,
                    'pending' => 100,
                    'currency' => 'USD',
                ]),
            ]);

            $connector->withMockClient($mockClient);
            $resource = new ProfilesResource($connector, $merchantUid);

            $params = ['currency' => 'USD'];
            $response = $resource->balance($profileUid, $params);

            expect($response->successful())->toBeTrue();
            expect($response->json('currency'))->toBe('USD');
        });
    });

    describe('PartnersResource getMerchantBalance method', function () {
        test('getMerchantBalance method returns correct response', function () {
            $connector = new OnlinePaymentPlatformConnector('test-key', true);
            $merchantUid = 'mer_123456789';

            $mockClient = new MockClient([
                GetPartnerMerchantBalanceRequest::class => MockResponse::make([
                    'object' => 'balance',
                    'merchant_uid' => 'mer_123456789',
                    'available' => 2500,
                    'pending' => 150.5,
                    'currency' => 'EUR',
                    'last_updated' => '2024-01-15T10:30:00Z',
                ]),
            ]);

            $connector->withMockClient($mockClient);
            $resource = new PartnersResource($connector);

            $response = $resource->getMerchantBalance($merchantUid);

            expect($response->successful())->toBeTrue();
            expect($response->json('object'))->toBe('balance');
            expect($response->json('merchant_uid'))->toBe('mer_123456789');
            expect($response->json('available'))->toBe(2500);
            expect($response->json('pending'))->toBe(150.5);
            expect($response->json('currency'))->toBe('EUR');
        });

        test('getMerchantBalance method with parameters', function () {
            $connector = new OnlinePaymentPlatformConnector('test-key', true);
            $merchantUid = 'mer_123456789';

            $mockClient = new MockClient([
                GetPartnerMerchantBalanceRequest::class => MockResponse::make([
                    'object' => 'balance',
                    'merchant_uid' => 'mer_123456789',
                    'available' => 1200.75,
                    'pending' => 300.25,
                    'currency' => 'GBP',
                ]),
            ]);

            $connector->withMockClient($mockClient);
            $resource = new PartnersResource($connector);

            $params = ['currency' => 'GBP'];
            $response = $resource->getMerchantBalance($merchantUid, $params);

            expect($response->successful())->toBeTrue();
            expect($response->json('currency'))->toBe('GBP');
        });
    });
});