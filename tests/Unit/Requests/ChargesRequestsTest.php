<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges\CreateChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Charges\ChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\CreateChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\GetChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\GetChargesRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

describe('Charges Requests', function () {
    describe('CreateChargeRequest', function () {
        test('it has correct method and endpoint', function () {
            $data = new CreateChargeData(
                type: 'balance',
                amount: 1000,
                from_owner_uid: 'mer_123',
                to_owner_uid: 'mer_456'
            );
            $request = new CreateChargeRequest($data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe('/charges');
        });

        test('it accepts CreateChargeData object', function () {
            $data = new CreateChargeData(
                type: 'balance',
                amount: 1000,
                from_owner_uid: 'mer_123',
                to_owner_uid: 'mer_456',
                description: 'Test charge'
            );
            $request = new CreateChargeRequest($data);

            $body = $request->body()->all();
            expect($body['type'])->toBe('balance');
            expect($body['amount'])->toBe(1000);
            expect($body['from_owner_uid'])->toBe('mer_123');
            expect($body['to_owner_uid'])->toBe('mer_456');
            expect($body['description'])->toBe('Test charge');
        });

        test('it accepts array data', function () {
            $data = [
                'type' => 'balance',
                'amount' => 2000,
                'from_owner_uid' => 'mer_789',
                'to_owner_uid' => 'mer_012',
            ];
            $request = new CreateChargeRequest($data);

            $body = $request->body()->all();
            expect($body['type'])->toBe('balance');
            expect($body['amount'])->toBe(2000);
            expect($body['from_owner_uid'])->toBe('mer_789');
            expect($body['to_owner_uid'])->toBe('mer_012');
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'uid' => 'cha_123456789',
                'type' => 'balance',
                'status' => 'completed',
                'amount' => 1000,
                'currency' => 'EUR',
                'from_owner_uid' => 'mer_123',
                'to_owner_uid' => 'mer_456',
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new CreateChargeRequest([]);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(ChargeData::class);
            expect($dto->uid)->toBe('cha_123456789');
            expect($dto->type)->toBe('balance');
            expect($dto->amount)->toBe(1000);
        });
    });

    describe('GetChargeRequest', function () {
        test('it has correct method and endpoint', function () {
            $request = new GetChargeRequest('cha_123456789');

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe('/charges/cha_123456789');
        });

        test('it uses charge UID in endpoint', function () {
            $chargeUid = 'cha_987654321';
            $request = new GetChargeRequest($chargeUid);

            expect($request->resolveEndpoint())->toBe("/charges/{$chargeUid}");
        });
    });

    describe('GetChargesRequest', function () {
        test('it has correct method and endpoint', function () {
            $request = new GetChargesRequest;

            expect($request->getMethod())->toBe(Method::GET);
            expect($request->resolveEndpoint())->toBe('/charges');
        });

        test('it accepts empty parameters', function () {
            $request = new GetChargesRequest;

            $query = $request->query()->all();
            expect($query)->toBe([]);
        });

        test('it accepts query parameters', function () {
            $params = [
                'limit' => 10,
                'offset' => 20,
                'status' => 'completed',
            ];
            $request = new GetChargesRequest($params);

            $query = $request->query()->all();
            expect($query['limit'])->toBe(10);
            expect($query['offset'])->toBe(20);
            expect($query['status'])->toBe('completed');
        });

        test('it handles complex query parameters', function () {
            $params = [
                'from_owner_uid' => 'mer_123',
                'to_owner_uid' => 'mer_456',
                'created_after' => '2024-01-01',
                'created_before' => '2024-12-31',
            ];
            $request = new GetChargesRequest($params);

            $query = $request->query()->all();
            expect($query['from_owner_uid'])->toBe('mer_123');
            expect($query['to_owner_uid'])->toBe('mer_456');
            expect($query['created_after'])->toBe('2024-01-01');
            expect($query['created_before'])->toBe('2024-12-31');
        });
    });
});
