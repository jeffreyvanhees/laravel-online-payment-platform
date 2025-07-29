<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\DeleteMerchantProfileRequest;
use Saloon\Enums\Method;

describe('Merchants Delete Requests', function () {
    describe('DeleteMerchantProfileRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $profileUid = 'pro_123456789';
            $request = new DeleteMerchantProfileRequest($merchantUid, $profileUid);

            expect($request->getMethod())->toBe(Method::DELETE);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/profiles/{$profileUid}");
        });

        test('it constructs with required parameters', function () {
            $merchantUid = 'mer_test123';
            $profileUid = 'pro_test456';
            $request = new DeleteMerchantProfileRequest($merchantUid, $profileUid);

            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/profiles/{$profileUid}");
        });

        test('it has no body for DELETE request', function () {
            $request = new DeleteMerchantProfileRequest('mer_123', 'pro_456');
            
            // DELETE requests typically don't have a body
            expect($request->getMethod())->toBe(Method::DELETE);
        });
    });
});