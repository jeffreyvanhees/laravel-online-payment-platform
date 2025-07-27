<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\SettlementData;

describe('SettlementData', function () {
    test('it can create SettlementData from array', function () {
        $data = SettlementData::from([
            'uid' => 'set_123456789',
            'status' => 'completed',
            'amount' => 15000,
            'currency' => 'EUR',
            'date' => '2024-01-15',
            'bank_account_uid' => 'ban_123456789',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T14:30:00Z',
            'transactions' => [
                ['uid' => 'tra_123', 'amount' => 10000],
                ['uid' => 'tra_456', 'amount' => 5000],
            ],
        ]);

        expect($data->uid)->toBe('set_123456789');
        expect($data->status)->toBe('completed');
        expect($data->amount)->toBe(15000);
        expect($data->currency)->toBe('EUR');
        expect($data->date)->toBe('2024-01-15');
        expect($data->bank_account_uid)->toBe('ban_123456789');
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T14:30:00Z');
        expect($data->transactions)->toBe([
            ['uid' => 'tra_123', 'amount' => 10000],
            ['uid' => 'tra_456', 'amount' => 5000],
        ]);
    });

    test('it can create SettlementData with minimal required data', function () {
        $data = new SettlementData(
            uid: 'set_987654321',
            status: 'pending',
            amount: 25000,
            currency: 'EUR',
            date: '2024-01-20'
        );

        expect($data->uid)->toBe('set_987654321');
        expect($data->status)->toBe('pending');
        expect($data->amount)->toBe(25000);
        expect($data->currency)->toBe('EUR');
        expect($data->date)->toBe('2024-01-20');
        expect($data->bank_account_uid)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
        expect($data->transactions)->toBeNull();
    });

    test('it can handle failed settlement data', function () {
        $data = SettlementData::from([
            'uid' => 'set_failed_123',
            'status' => 'failed',
            'amount' => 5000,
            'currency' => 'EUR',
            'date' => '2024-01-15',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T11:00:00Z',
        ]);

        expect($data->uid)->toBe('set_failed_123');
        expect($data->status)->toBe('failed');
        expect($data->amount)->toBe(5000);
        expect($data->updated_at)->toBe('2024-01-15T11:00:00Z');
    });
});
