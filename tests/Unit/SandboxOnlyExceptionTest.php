<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\SandboxOnlyException;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;

describe('SandboxOnlyException', function () {
    test('it has correct message and code', function () {
        $exception = new SandboxOnlyException();
        
        expect($exception->getMessage())->toBe('This method is only available in sandbox environment. It cannot be used in production.');
        expect($exception->getCode())->toBe(400);
    });

    test('it accepts custom method name', function () {
        $exception = new SandboxOnlyException('updateStatus method for merchants');
        
        expect($exception->getMessage())->toBe('updateStatus method for merchants is only available in sandbox environment. It cannot be used in production.');
        expect($exception->getCode())->toBe(400);
    });

    test('resources throw exception when updateStatus is called in production', function () {
        $connector = new OnlinePaymentPlatformConnector('test-key', false); // production mode
        
        // Test bank accounts
        expect(fn() => $connector->merchants()->bankAccounts('mer_123')->updateStatus('ba_123', 'approved'))
            ->toThrow(SandboxOnlyException::class, 'updateStatus method for bank accounts is only available in sandbox environment. It cannot be used in production.');
            
        // Test contacts  
        expect(fn() => $connector->merchants()->contacts('mer_123')->updateStatus('con_123', 'verified'))
            ->toThrow(SandboxOnlyException::class, 'updateStatus method for contacts is only available in sandbox environment. It cannot be used in production.');
            
        // Test UBOs
        expect(fn() => $connector->merchants()->ubos('mer_123')->updateStatus('ubo_123', 'verified'))
            ->toThrow(SandboxOnlyException::class, 'updateStatus method for UBOs is only available in sandbox environment. It cannot be used in production.');
            
        // Test merchants
        expect(fn() => $connector->merchants()->updateStatus('mer_123', 'live'))
            ->toThrow(SandboxOnlyException::class, 'updateStatus method for merchants is only available in sandbox environment. It cannot be used in production.');
    });

    test('resources work normally in sandbox mode', function () {
        $connector = new OnlinePaymentPlatformConnector('test-key', true); // sandbox mode
        
        // These should not throw exceptions (though they may fail due to network/API issues)
        expect($connector->isSandbox())->toBeTrue();
        
        // The methods should be callable without throwing SandboxOnlyException
        // We're just testing the exception check, not the actual API calls
        try {
            $connector->merchants()->bankAccounts('mer_123')->updateStatus('ba_123', 'approved');
        } catch (SandboxOnlyException $e) {
            throw new Exception('SandboxOnlyException should not be thrown in sandbox mode');
        } catch (Exception $e) {
            // Other exceptions (network, API, etc.) are expected and ignored for this test
        }
    });
});