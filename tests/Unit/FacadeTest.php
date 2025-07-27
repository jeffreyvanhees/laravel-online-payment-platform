<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformFacade;

describe('OnlinePaymentPlatformFacade', function () {
    test('it has the correct facade accessor method', function () {
        $reflection = new \ReflectionClass(OnlinePaymentPlatformFacade::class);

        expect($reflection->hasMethod('getFacadeAccessor'))->toBeTrue();

        $method = $reflection->getMethod('getFacadeAccessor');
        expect($method->isProtected())->toBeTrue();
        expect($method->isStatic())->toBeTrue();
    });

    test('it extends Laravel facade correctly', function () {
        $reflection = new \ReflectionClass(OnlinePaymentPlatformFacade::class);

        expect($reflection->isSubclassOf(\Illuminate\Support\Facades\Facade::class))->toBeTrue();
    });

    test('facade accessor returns correct class name', function () {
        // Create an instance to test the method without Laravel container
        $facade = new class extends OnlinePaymentPlatformFacade
        {
            public static function testGetFacadeAccessor()
            {
                return self::getFacadeAccessor();
            }
        };

        expect($facade::testGetFacadeAccessor())->toBe(OnlinePaymentPlatformConnector::class);
    });
});
