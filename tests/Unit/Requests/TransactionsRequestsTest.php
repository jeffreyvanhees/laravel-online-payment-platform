<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\TransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\CreateTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\DeleteTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\GetTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\GetTransactionsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\UpdateTransactionRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

describe('Transactions Requests', function () {
    describe('CreateTransactionRequest', function () {
        test('it has correct method and endpoint', function () {
            $data = [
                'merchant_uid' => 'mer_123456789',
                'amount' => 2500,
                'currency' => 'EUR',
            ];
            $request = new CreateTransactionRequest($data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe('/transactions');
        });

        test('it accepts CreateTransactionData object', function () {
            $data = new CreateTransactionData(
                merchant_uid: 'mer_123456789',
                total_price: 3000,
                return_url: 'https://example.com/return',
                notify_url: 'https://example.com/notify',
                products: []
            );
            $request = new CreateTransactionRequest($data);

            $body = $request->body()->all();
            expect($body['merchant_uid'])->toBe('mer_123456789');
            expect($body['total_price'])->toBe(3000);
            expect($body['return_url'])->toBe('https://example.com/return');
            expect($body['notify_url'])->toBe('https://example.com/notify');
        });

        test('it accepts array data', function () {
            $data = [
                'merchant_uid' => 'mer_987654321',
                'amount' => 1500,
                'currency' => 'EUR',
                'payment_method' => 'ideal',
                'return_url' => 'https://example.com/return',
            ];
            $request = new CreateTransactionRequest($data);

            $body = $request->body()->all();
            expect($body['merchant_uid'])->toBe('mer_987654321');
            expect($body['amount'])->toBe(1500);
            expect($body['currency'])->toBe('EUR');
            expect($body['payment_method'])->toBe('ideal');
            expect($body['return_url'])->toBe('https://example.com/return');
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'uid' => 'tra_123456789',
                'status' => 'created',
                'merchant_uid' => 'mer_123456789',
                'amount' => 2500,
                'currency' => 'EUR',
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new CreateTransactionRequest([]);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(TransactionData::class);
            expect($dto->uid)->toBe('tra_123456789');
            expect($dto->status)->toBe('created');
            expect($dto->amount)->toBe(2500);
        });
    });

    describe('GetTransactionRequest', function () {
        test('it has correct method and endpoint', function () {
            $transactionUid = 'tra_123456789';
            $request = new GetTransactionRequest($transactionUid);

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe("/transactions/{$transactionUid}");
        });
    });

    describe('GetTransactionsRequest', function () {
        test('it has correct method and endpoint', function () {
            $request = new GetTransactionsRequest;

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe('/transactions');
        });

        test('it accepts query parameters', function () {
            $params = [
                'merchant_uid' => 'mer_123456789',
                'status' => 'completed',
                'limit' => 50,
                'offset' => 100,
            ];
            $request = new GetTransactionsRequest($params);

            $query = $request->query()->all();
            expect($query['merchant_uid'])->toBe('mer_123456789');
            expect($query['status'])->toBe('completed');
            expect($query['limit'])->toBe(50);
            expect($query['offset'])->toBe(100);
        });
    });

    describe('UpdateTransactionRequest', function () {
        test('it has correct method and endpoint', function () {
            $transactionUid = 'tra_123456789';
            $data = ['status' => 'cancelled'];
            $request = new UpdateTransactionRequest($transactionUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/transactions/{$transactionUid}");
        });

        test('it accepts update data', function () {
            $data = [
                'status' => 'refunded',
                'metadata' => ['reason' => 'customer_request'],
            ];
            $request = new UpdateTransactionRequest('tra_123', $data);

            $body = $request->body()->all();
            expect($body['status'])->toBe('refunded');
            expect($body['metadata'])->toBe(['reason' => 'customer_request']);
        });
    });

    describe('DeleteTransactionRequest', function () {
        test('it has correct method and endpoint', function () {
            $transactionUid = 'tra_123456789';
            $request = new DeleteTransactionRequest($transactionUid);

            expect($request->getMethod())->toBe(Method::DELETE);
            expect($request->resolveEndpoint())->toBe("/transactions/{$transactionUid}");
        });
    });
});
