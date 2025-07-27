<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformServiceProvider;

describe('OnlinePaymentPlatformServiceProvider', function () {
    test('it can be instantiated', function () {
        $provider = new OnlinePaymentPlatformServiceProvider(app());

        expect($provider)->toBeInstanceOf(OnlinePaymentPlatformServiceProvider::class);
    });

    test('it has boot method', function () {
        $provider = new OnlinePaymentPlatformServiceProvider(app());

        expect(method_exists($provider, 'boot'))->toBeTrue();
    });

    test('it has register method', function () {
        $provider = new OnlinePaymentPlatformServiceProvider(app());

        expect(method_exists($provider, 'register'))->toBeTrue();
    });
});
