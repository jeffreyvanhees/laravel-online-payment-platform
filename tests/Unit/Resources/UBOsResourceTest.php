<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\CreateMerchantUBORequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantUBORequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantUBOsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\UBOsResource;
use Saloon\Http\BaseResource;
use Saloon\Http\Connector;
use Saloon\Http\Response;

describe('UBOsResource', function () {
    beforeEach(function () {
        $this->connector = Mockery::mock(Connector::class);
        $this->merchantUid = 'mer_123456789';
        $this->resource = new UBOsResource($this->connector, $this->merchantUid);
    });

    test('it extends BaseResource', function () {
        expect($this->resource)->toBeInstanceOf(BaseResource::class);
    });

    test('it stores merchant UID', function () {
        $reflection = new ReflectionClass($this->resource);
        $property = $reflection->getProperty('merchantUid');
        $property->setAccessible(true);

        expect($property->getValue($this->resource))->toBe($this->merchantUid);
    });

    test('it can create UBO', function () {
        $data = [
            'name_first' => 'John',
            'name_last' => 'Doe',
            'date_of_birth' => '1980-01-15',
            'country_of_residence' => 'NLD',
            'is_decision_maker' => true,
            'percentage_of_shares' => 25.5,
        ];

        $response = Mockery::mock(Response::class);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::type(CreateMerchantUBORequest::class))
            ->andReturn($response);

        $result = $this->resource->create($data);

        expect($result)->toBe($response);
    });

    test('it passes correct data to CreateMerchantUBORequest', function () {
        $data = [
            'name_first' => 'Jane',
            'name_last' => 'Smith',
            'date_of_birth' => '1985-03-20',
            'country_of_residence' => 'DEU',
            'is_pep' => false,
        ];

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($data) {
                if (! $request instanceof CreateMerchantUBORequest) {
                    return false;
                }

                // Use reflection to access protected properties
                $reflection = new ReflectionClass($request);
                $merchantUidProp = $reflection->getProperty('merchantUid');
                $merchantUidProp->setAccessible(true);
                $dataProp = $reflection->getProperty('data');
                $dataProp->setAccessible(true);

                return $merchantUidProp->getValue($request) === $this->merchantUid
                    && $dataProp->getValue($request) === $data;
            }))
            ->andReturn(Mockery::mock(Response::class));

        $this->resource->create($data);
    });

    test('it can get specific UBO', function () {
        $uboUid = 'ubo_123456789';
        $response = Mockery::mock(Response::class);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::type(GetMerchantUBORequest::class))
            ->andReturn($response);

        $result = $this->resource->get($uboUid);

        expect($result)->toBe($response);
    });

    test('it passes correct parameters to GetMerchantUBORequest', function () {
        $uboUid = 'ubo_987654321';

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($uboUid) {
                if (! $request instanceof GetMerchantUBORequest) {
                    return false;
                }

                // Use reflection to access protected properties
                $reflection = new ReflectionClass($request);
                $merchantUidProp = $reflection->getProperty('merchantUid');
                $merchantUidProp->setAccessible(true);
                $uboUidProp = $reflection->getProperty('uboUid');
                $uboUidProp->setAccessible(true);

                return $merchantUidProp->getValue($request) === $this->merchantUid
                    && $uboUidProp->getValue($request) === $uboUid;
            }))
            ->andReturn(Mockery::mock(Response::class));

        $this->resource->get($uboUid);
    });

    test('it can list UBOs with default parameters', function () {
        $response = Mockery::mock(Response::class);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::type(GetMerchantUBOsRequest::class))
            ->andReturn($response);

        $result = $this->resource->list();

        expect($result)->toBe($response);
    });

    test('it can list UBOs with custom parameters', function () {
        $params = [
            'limit' => 20,
            'offset' => 40,
            'is_decision_maker' => true,
        ];

        $response = Mockery::mock(Response::class);

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::type(GetMerchantUBOsRequest::class))
            ->andReturn($response);

        $result = $this->resource->list($params);

        expect($result)->toBe($response);
    });

    test('it passes correct parameters to GetMerchantUBOsRequest', function () {
        $params = [
            'is_pep' => false,
            'country_of_residence' => 'NLD',
        ];

        $this->connector
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::on(function ($request) use ($params) {
                if (! $request instanceof GetMerchantUBOsRequest) {
                    return false;
                }

                // Use reflection to access protected properties
                $reflection = new ReflectionClass($request);
                $merchantUidProp = $reflection->getProperty('merchantUid');
                $merchantUidProp->setAccessible(true);
                $paramsProp = $reflection->getProperty('params');
                $paramsProp->setAccessible(true);

                return $merchantUidProp->getValue($request) === $this->merchantUid
                    && $paramsProp->getValue($request) === $params;
            }))
            ->andReturn(Mockery::mock(Response::class));

        $this->resource->list($params);
    });
});
