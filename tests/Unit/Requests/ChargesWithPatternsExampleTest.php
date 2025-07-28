<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges\CreateChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Charges\ChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\CreateChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\GetChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\GetChargesRequest;
use Saloon\Enums\Method;
use Tests\Traits\AssertsRequests;

require_once __DIR__.'/../../Helpers/TestHelpers.php';
require_once __DIR__.'/../../Datasets/RequestDatasets.php';
require_once __DIR__.'/../../Traits/AssertsRequests.php';

uses(AssertsRequests::class);

describe('Charges Request Patterns Example', function () {

    // Single test covers all create charge scenarios
    test('create charge request', function (array $data) {
        $request = new CreateChargeRequest($data);

        $this->assertRequest($request)
            ->hasMethod(Method::POST)
            ->hasEndpoint('/charges')
            ->hasExactBody($data);
    })->with('charge_scenarios');

    // Test with both DTO and array
    test('create charge accepts dto or array', function ($input) {
        $request = new CreateChargeRequest($input);

        $expectedBody = $input instanceof CreateChargeData
            ? $input->toArray()
            : $input;

        $this->assertRequest($request)
            ->hasMethod(Method::POST)
            ->hasEndpoint('/charges')
            ->hasBody($expectedBody);

        // Test DTO creation from response
        $responseData = array_merge($expectedBody, [
            'uid' => 'cha_created_123',
            'status' => 'completed',
            'currency' => 'EUR',
        ]);

        $response = mockJsonResponse($responseData);
        $dto = $request->createDtoFromResponse($response);

        expect($dto)->toBeInstanceOf(ChargeData::class);
        expect($dto->uid)->toBe('cha_created_123');
        expect($dto->amount)->toBe($expectedBody['amount'] ?? $expectedBody['total_price']);
    })->with([
        'array input' => [[
            'type' => 'balance',
            'amount' => 1000,
            'from_owner_uid' => 'mer_123',
            'to_owner_uid' => 'mer_456',
        ]],
        'dto input' => [new CreateChargeData(
            type: 'balance',
            amount: 2000,
            from_owner_uid: 'mer_789',
            to_owner_uid: 'mer_012',
            description: 'Via DTO'
        )],
    ]);

    // Single line test for simple requests
    test('get charge request', function () {
        $request = new GetChargeRequest('cha_123');
        expect($request->getMethod())->toBe(Method::GET);
        expect($request->resolveEndpoint())->toBe('/charges/cha_123');
    });

    // Table-driven tests for query parameters
    test('get charges with filters', function ($params, $description) {
        $request = new GetChargesRequest($params);

        $this->assertRequest($request)
            ->hasMethod(Method::GET)
            ->hasEndpoint('/charges')
            ->hasQuery($params);
    })->with([
        'pagination' => [['limit' => 20, 'offset' => 40], 'paginated results'],
        'status filter' => [['status' => 'completed'], 'only completed charges'],
        'owner filter' => [['from_owner_uid' => 'mer_123'], 'charges from specific merchant'],
        'date range' => [['created_after' => '2024-01-01', 'created_before' => '2024-12-31'], 'charges in 2024'],
        'combined' => [[
            'status' => 'completed',
            'from_owner_uid' => 'mer_123',
            'limit' => 50,
            'created_after' => '2024-01-01',
        ], 'complex filter combination'],
    ]);
});
