<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\MigrateMerchantRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\UpdateMerchantRequest;
use Saloon\Enums\Method;
use Saloon\Http\Response;

describe('Merchants Migration and Update Requests', function () {
    describe('MigrateMerchantRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $migrationData = [
                'type' => 'business',
                'legal_name' => 'New Business Name B.V.',
                'coc_nr' => '12345678',
            ];
            $request = new MigrateMerchantRequest($merchantUid, $migrationData);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/migrate");
        });

        test('it accepts migration data', function () {
            $migrationData = [
                'type' => 'business',
                'legal_name' => 'Migrated Company B.V.',
                'coc_nr' => '87654321',
                'vat_nr' => 'NL001234567B01',
            ];
            $request = new MigrateMerchantRequest('mer_123', $migrationData);

            $body = $request->body()->all();
            expect($body['type'])->toBe('business');
            expect($body['legal_name'])->toBe('Migrated Company B.V.');
            expect($body['coc_nr'])->toBe('87654321');
            expect($body['vat_nr'])->toBe('NL001234567B01');
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'uid' => 'mer_123456789',
                'type' => 'business',
                'status' => 'pending',
                'legal_name' => 'Migrated Company B.V.',
                'coc_nr' => '87654321',
                'country' => 'NLD',
                'emailaddress' => 'business@example.com',
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new MigrateMerchantRequest('mer_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(MerchantData::class);
            expect($dto->uid)->toBe('mer_123456789');
            expect($dto->type)->toBe('business');
            expect($dto->legal_name)->toBe('Migrated Company B.V.');
        });
    });

    describe('UpdateMerchantRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $updateData = [
                'emailaddress' => 'newemail@example.com',
                'notify_url' => 'https://example.com/webhook',
            ];
            $request = new UpdateMerchantRequest($merchantUid, $updateData);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}");
        });

        test('it accepts update data', function () {
            $updateData = [
                'emailaddress' => 'updated@example.com',
                'notify_url' => 'https://newdomain.com/webhook',
                'return_url' => 'https://newdomain.com/return',
                'metadata' => ['updated_by' => 'admin'],
            ];
            $request = new UpdateMerchantRequest('mer_123', $updateData);

            $body = $request->body()->all();
            expect($body['emailaddress'])->toBe('updated@example.com');
            expect($body['notify_url'])->toBe('https://newdomain.com/webhook');
            expect($body['return_url'])->toBe('https://newdomain.com/return');
            expect($body['metadata'])->toBe(['updated_by' => 'admin']);
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'uid' => 'mer_123456789',
                'type' => 'consumer',
                'status' => 'live',
                'country' => 'NLD',
                'emailaddress' => 'updated@example.com',
                'notify_url' => 'https://newdomain.com/webhook',
                'return_url' => 'https://newdomain.com/return',
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new UpdateMerchantRequest('mer_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(MerchantData::class);
            expect($dto->uid)->toBe('mer_123456789');
            expect($dto->emailaddress)->toBe('updated@example.com');
            expect($dto->notify_url)->toBe('https://newdomain.com/webhook');
        });
    });
});
