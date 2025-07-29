<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantPaymentMethodsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\PaymentMethodsResource;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('Payment Methods Resource', function () {
    beforeEach(function () {
        $this->connector = new OnlinePaymentPlatformConnector('test-key', true);
        $this->merchantUid = 'mer_123456789';
        $this->resource = new PaymentMethodsResource($this->connector, $this->merchantUid);
    });

    test('list payment methods', function () {
        $mockClient = new MockClient([
            GetMerchantPaymentMethodsRequest::class => MockResponse::make([
                'object' => 'list',
                'data' => [
                    [
                        'method' => 'ideal',
                        'enabled' => true,
                        'currency' => 'EUR',
                    ],
                    [
                        'method' => 'creditcard',
                        'enabled' => true,
                        'currency' => 'EUR',
                    ],
                ],
            ]),
        ]);

        $this->connector->withMockClient($mockClient);

        $response = $this->resource->list();

        expect($response->successful())->toBeTrue();
        expect($response->json('data'))->toHaveCount(2);
        expect($response->json('data.0.method'))->toBe('ideal');
        expect($response->json('data.1.method'))->toBe('creditcard');
    });

    test('list payment methods with parameters', function () {
        $mockClient = new MockClient([
            GetMerchantPaymentMethodsRequest::class => MockResponse::make([
                'object' => 'list',
                'data' => [
                    [
                        'method' => 'ideal',
                        'enabled' => true,
                        'currency' => 'EUR',
                    ],
                ],
            ]),
        ]);

        $this->connector->withMockClient($mockClient);

        $params = [
            'method_type' => 'ideal',
            'enabled' => true,
        ];

        $response = $this->resource->list($params);

        expect($response->successful())->toBeTrue();
        expect($response->json('data'))->toHaveCount(1);
        expect($response->json('data.0.method'))->toBe('ideal');
    });
});
