<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\CreateMandateRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\CreateMandateTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\DeleteMandateRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\GetMandateRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\GetMandatesRequest;
use Saloon\Enums\Method;

describe('Mandates Requests', function () {
    describe('CreateMandateRequest', function () {
        test('it has correct method and endpoint', function () {
            $data = [
                'merchant_uid' => 'mer_123456789',
                'return_url' => 'https://example.com/return',
                'notify_url' => 'https://example.com/notify',
            ];
            $request = new CreateMandateRequest($data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe('/mandates');
        });

        test('it accepts mandate data', function () {
            $data = [
                'merchant_uid' => 'mer_123456789',
                'return_url' => 'https://example.com/return',
                'notify_url' => 'https://example.com/notify',
                'description' => 'Monthly subscription mandate',
                'reference' => 'SUB-2024-001',
                'metadata' => ['subscription_id' => 'sub_123'],
            ];
            $request = new CreateMandateRequest($data);

            $body = $request->body()->all();
            expect($body['merchant_uid'])->toBe('mer_123456789');
            expect($body['return_url'])->toBe('https://example.com/return');
            expect($body['notify_url'])->toBe('https://example.com/notify');
            expect($body['description'])->toBe('Monthly subscription mandate');
            expect($body['reference'])->toBe('SUB-2024-001');
            expect($body['metadata'])->toBe(['subscription_id' => 'sub_123']);
        });
    });

    describe('CreateMandateTransactionRequest', function () {
        test('it has correct method and endpoint', function () {
            $mandateUid = 'man_123456789';
            $data = [
                'total_price' => 2500,
                'currency' => 'EUR',
                'description' => 'Monthly payment',
            ];
            $request = new CreateMandateTransactionRequest($mandateUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/mandates/{$mandateUid}/transactions");
        });

        test('it includes mandate UID in endpoint', function () {
            $mandateUid = 'man_987654321';
            $data = ['total_price' => 1000, 'currency' => 'EUR'];
            $request = new CreateMandateTransactionRequest($mandateUid, $data);

            expect($request->resolveEndpoint())->toBe("/mandates/{$mandateUid}/transactions");
        });

        test('it accepts transaction data', function () {
            $data = [
                'total_price' => 5000,
                'currency' => 'EUR',
                'description' => 'Annual subscription',
                'metadata' => ['payment_cycle' => 'annual'],
                'return_url' => 'https://example.com/success',
            ];
            $request = new CreateMandateTransactionRequest('man_123', $data);

            $body = $request->body()->all();
            expect($body['total_price'])->toBe(5000);
            expect($body['currency'])->toBe('EUR');
            expect($body['description'])->toBe('Annual subscription');
            expect($body['metadata'])->toBe(['payment_cycle' => 'annual']);
            expect($body['return_url'])->toBe('https://example.com/success');
        });
    });

    describe('DeleteMandateRequest', function () {
        test('it has correct method and endpoint', function () {
            $mandateUid = 'man_123456789';
            $request = new DeleteMandateRequest($mandateUid);

            expect($request->getMethod())->toBe(Method::DELETE);
            expect($request->resolveEndpoint())->toBe("/mandates/{$mandateUid}");
        });

        test('it uses mandate UID in endpoint', function () {
            $mandateUid = 'man_to_delete_456';
            $request = new DeleteMandateRequest($mandateUid);

            expect($request->resolveEndpoint())->toBe("/mandates/{$mandateUid}");
        });
    });

    describe('GetMandateRequest', function () {
        test('it has correct method and endpoint', function () {
            $mandateUid = 'man_123456789';
            $request = new GetMandateRequest($mandateUid);

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe("/mandates/{$mandateUid}");
        });
    });

    describe('GetMandatesRequest', function () {
        test('it has correct method and endpoint', function () {
            $request = new GetMandatesRequest;

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe('/mandates');
        });

        test('it accepts query parameters', function () {
            $params = [
                'merchant_uid' => 'mer_123456789',
                'status' => 'active',
                'limit' => 20,
                'offset' => 40,
            ];
            $request = new GetMandatesRequest($params);

            $query = $request->query()->all();
            expect($query['merchant_uid'])->toBe('mer_123456789');
            expect($query['status'])->toBe('active');
            expect($query['limit'])->toBe(20);
            expect($query['offset'])->toBe(40);
        });
    });
});
