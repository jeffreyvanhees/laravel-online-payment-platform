<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\CreateMerchantUBORequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantUBORequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantUBOsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\UBOsResource;

require_once __DIR__.'/ResourceTestCase.php';

describe('UBOs Resource', function () {
    beforeEach(function () {
        $this->connector = mockConnector();
        $this->merchantUid = 'mer_123456789';
        $this->resource = new UBOsResource($this->connector, $this->merchantUid);
    });

    dataset('ubo_data', [
        'minimal' => [[
            'name_first' => 'John',
            'name_last' => 'Doe',
            'date_of_birth' => '1980-01-15',
            'country_of_residence' => 'NLD',
        ]],
        'with decision maker' => [[
            'name_first' => 'Jane',
            'name_last' => 'Smith',
            'date_of_birth' => '1985-03-20',
            'country_of_residence' => 'DEU',
            'is_decision_maker' => true,
            'percentage_of_shares' => 25.5,
        ]],
        'with pep status' => [[
            'name_first' => 'Robert',
            'name_last' => 'Johnson',
            'date_of_birth' => '1975-07-10',
            'country_of_residence' => 'USA',
            'is_pep' => true,
            'is_decision_maker' => false,
            'percentage_of_shares' => 15.0,
        ]],
    ]);

    test('create UBO', function (array $data) {
        $response = mockJsonResponse(['uid' => 'ubo_created_123']);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($data) {
                expect($request)->toBeInstanceOf(CreateMerchantUBORequest::class);
                assertProtectedProperties($request, [
                    'merchantUid' => $this->merchantUid,
                    'data' => $data,
                ]);

                return true;
            }))
            ->andReturn($response);

        $result = $this->resource->create($data);

        expect($result)->toBe($response);
    })->with('ubo_data');

    test('get UBO', function () {
        $uboUid = 'ubo_123456789';
        $response = mockJsonResponse(['uid' => $uboUid, 'name_first' => 'John']);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($uboUid) {
                expect($request)->toBeInstanceOf(GetMerchantUBORequest::class);
                assertProtectedProperties($request, [
                    'merchantUid' => $this->merchantUid,
                    'uboUid' => $uboUid,
                ]);

                return true;
            }))
            ->andReturn($response);

        $result = $this->resource->get($uboUid);

        expect($result)->toBe($response);
    });

    test('list UBOs', function (array $params) {
        $response = mockJsonResponse(['data' => [], 'meta' => ['total' => 0]]);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($params) {
                expect($request)->toBeInstanceOf(GetMerchantUBOsRequest::class);
                assertProtectedProperties($request, [
                    'merchantUid' => $this->merchantUid,
                    'params' => $params,
                ]);

                return true;
            }))
            ->andReturn($response);

        $result = $this->resource->list($params);

        expect($result)->toBe($response);
    })->with([
        'no filters' => [[]],
        'with decision maker filter' => [['is_decision_maker' => true]],
        'with pep filter' => [['is_pep' => false]],
        'with country filter' => [['country_of_residence' => 'NLD']],
        'with pagination' => [['limit' => 20, 'offset' => 40]],
        'complex filters' => [[
            'is_decision_maker' => true,
            'is_pep' => false,
            'country_of_residence' => 'NLD',
            'limit' => 50,
        ]],
    ]);
});
