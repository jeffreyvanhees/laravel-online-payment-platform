<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Withdrawals\WithdrawalData;

describe('WithdrawalData', function () {
    test('it can create WithdrawalData from array', function () {
        $data = WithdrawalData::from([
            'uid' => 'wit_123456789',
            'status' => 'completed',
            'merchant_uid' => 'mer_123456789',
            'amount' => 50000,
            'currency' => 'EUR',
            'bank_account_uid' => 'ban_123456789',
            'description' => 'Monthly withdrawal',
            'reference' => 'WIT-2024-001',
            'metadata' => ['batch_id' => 'batch_123', 'priority' => 'high'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T14:30:00Z',
            'processed_at' => '2024-01-15T14:45:00Z',
        ]);

        expect($data->uid)->toBe('wit_123456789');
        expect($data->status)->toBe('completed');
        expect($data->merchant_uid)->toBe('mer_123456789');
        expect($data->amount)->toBe(50000);
        expect($data->currency)->toBe('EUR');
        expect($data->bank_account_uid)->toBe('ban_123456789');
        expect($data->description)->toBe('Monthly withdrawal');
        expect($data->reference)->toBe('WIT-2024-001');
        expect($data->metadata)->toBe(['batch_id' => 'batch_123', 'priority' => 'high']);
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T14:30:00Z');
        expect($data->processed_at)->toBe('2024-01-15T14:45:00Z');
    });

    test('it can create WithdrawalData with minimal required data', function () {
        $data = new WithdrawalData(
            uid: 'wit_987654321',
            status: 'pending',
            merchant_uid: 'mer_987654321',
            amount: 25000,
            currency: 'EUR',
            bank_account_uid: 'ban_987654321'
        );

        expect($data->uid)->toBe('wit_987654321');
        expect($data->status)->toBe('pending');
        expect($data->merchant_uid)->toBe('mer_987654321');
        expect($data->amount)->toBe(25000);
        expect($data->currency)->toBe('EUR');
        expect($data->bank_account_uid)->toBe('ban_987654321');
        expect($data->description)->toBeNull();
        expect($data->reference)->toBeNull();
        expect($data->metadata)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
        expect($data->processed_at)->toBeNull();
    });

    test('it can handle failed withdrawal data', function () {
        $data = WithdrawalData::from([
            'uid' => 'wit_failed_123',
            'status' => 'failed',
            'merchant_uid' => 'mer_123456789',
            'amount' => 10000,
            'currency' => 'EUR',
            'bank_account_uid' => 'ban_123456789',
            'description' => 'Failed withdrawal attempt',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T11:00:00Z',
        ]);

        expect($data->uid)->toBe('wit_failed_123');
        expect($data->status)->toBe('failed');
        expect($data->amount)->toBe(10000);
        expect($data->description)->toBe('Failed withdrawal attempt');
        expect($data->processed_at)->toBeNull();
    });
});
