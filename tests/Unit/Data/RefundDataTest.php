<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;

describe('RefundData', function () {
    test('it can create RefundData from array', function () {
        $data = RefundData::from([
            'uid' => 'ref_123456789',
            'status' => 'completed',
            'amount' => 2500,
            'currency' => 'EUR',
            'payout_description' => 'Refund for order #12345',
            'message' => 'Customer requested refund due to defective item',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T14:30:00Z',
            'processed_at' => '2024-01-15T14:45:00Z',
        ]);

        expect($data->uid)->toBe('ref_123456789');
        expect($data->status)->toBe('completed');
        expect($data->amount)->toBe(2500);
        expect($data->currency)->toBe('EUR');
        expect($data->payout_description)->toBe('Refund for order #12345');
        expect($data->message)->toBe('Customer requested refund due to defective item');
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T14:30:00Z');
        expect($data->processed_at)->toBe('2024-01-15T14:45:00Z');
    });

    test('it can create RefundData with minimal required data', function () {
        $data = new RefundData(
            uid: 'ref_987654321',
            status: 'pending',
            amount: 1000,
            currency: 'EUR'
        );

        expect($data->uid)->toBe('ref_987654321');
        expect($data->status)->toBe('pending');
        expect($data->amount)->toBe(1000);
        expect($data->currency)->toBe('EUR');
        expect($data->payout_description)->toBeNull();
        expect($data->message)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
        expect($data->processed_at)->toBeNull();
    });

    test('it can handle failed refund data', function () {
        $data = RefundData::from([
            'uid' => 'ref_failed_123',
            'status' => 'failed',
            'amount' => 1500,
            'currency' => 'EUR',
            'message' => 'Insufficient funds for refund',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T11:00:00Z',
        ]);

        expect($data->uid)->toBe('ref_failed_123');
        expect($data->status)->toBe('failed');
        expect($data->amount)->toBe(1500);
        expect($data->message)->toBe('Insufficient funds for refund');
        expect($data->processed_at)->toBeNull();
    });
});
