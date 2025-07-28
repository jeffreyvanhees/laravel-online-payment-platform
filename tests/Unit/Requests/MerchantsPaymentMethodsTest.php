<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantPaymentMethodsRequest;
use Saloon\Enums\Method;

describe('Merchants Payment Methods Requests', function () {
    describe('GetMerchantPaymentMethodsRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $request = new GetMerchantPaymentMethodsRequest($merchantUid);

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/payment_methods");
        });

        test('it accepts empty parameters', function () {
            $request = new GetMerchantPaymentMethodsRequest('mer_123');

            $query = $request->query()->all();
            expect($query)->toBe([]);
        });

        test('it accepts query parameters', function () {
            $params = [
                'limit' => 20,
                'offset' => 40,
                'status' => 'active',
            ];
            $request = new GetMerchantPaymentMethodsRequest('mer_123', $params);

            $query = $request->query()->all();
            expect($query['limit'])->toBe(20);
            expect($query['offset'])->toBe(40);
            expect($query['status'])->toBe('active');
        });

        test('it handles complex query parameters', function () {
            $params = [
                'method_type' => 'ideal',
                'currency' => 'EUR',
                'enabled' => true,
                'created_after' => '2024-01-01',
            ];
            $request = new GetMerchantPaymentMethodsRequest('mer_456', $params);

            $query = $request->query()->all();
            expect($query['method_type'])->toBe('ideal');
            expect($query['currency'])->toBe('EUR');
            expect($query['enabled'])->toBeTrue();
            expect($query['created_after'])->toBe('2024-01-01');
        });
    });
});