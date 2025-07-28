<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantProfileBalanceRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners\GetPartnerMerchantBalanceRequest;

describe('Balance Requests', function () {
    test('GetMerchantProfileBalanceRequest has correct properties', function () {
        $merchantUid = 'mer_123456789';
        $profileUid = 'pro_987654321';
        $params = ['currency' => 'EUR'];

        $request = new GetMerchantProfileBalanceRequest($merchantUid, $profileUid, $params);

        expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/profiles/{$profileUid}/balance");
        expect($request->getMethod()->value)->toBe('GET');
    });

    test('GetMerchantProfileBalanceRequest handles empty params', function () {
        $merchantUid = 'mer_123456789';
        $profileUid = 'pro_987654321';

        $request = new GetMerchantProfileBalanceRequest($merchantUid, $profileUid);

        expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/profiles/{$profileUid}/balance");
        expect($request->getMethod()->value)->toBe('GET');
    });

    test('GetPartnerMerchantBalanceRequest has correct properties', function () {
        $merchantUid = 'mer_123456789';
        $params = ['currency' => 'EUR'];

        $request = new GetPartnerMerchantBalanceRequest($merchantUid, $params);

        expect($request->resolveEndpoint())->toBe("/partners/merchants/{$merchantUid}/balance");
        expect($request->getMethod()->value)->toBe('GET');
    });

    test('GetPartnerMerchantBalanceRequest handles empty params', function () {
        $merchantUid = 'mer_123456789';

        $request = new GetPartnerMerchantBalanceRequest($merchantUid);

        expect($request->resolveEndpoint())->toBe("/partners/merchants/{$merchantUid}/balance");
        expect($request->getMethod()->value)->toBe('GET');
    });
});