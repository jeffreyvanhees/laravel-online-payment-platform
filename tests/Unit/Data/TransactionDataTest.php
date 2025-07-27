<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\TransactionData;

describe('TransactionData', function () {
    test('it can create TransactionData from array', function () {
        $data = TransactionData::from([
            'uid' => 'tra_123456789',
            'status' => 'completed',
            'merchant_uid' => 'mer_123456789',
            'amount' => 2500,
            'currency' => 'EUR',
            'redirect_url' => 'https://payment.example.com/pay/tra_123456789',
            'return_url' => 'https://store.example.com/success',
            'notify_url' => 'https://store.example.com/notify',
            'payment_method' => 'ideal',
            'payment_flow' => 'hosted',
            'payment_details' => ['bank' => 'ABNANL2A', 'account' => '****1234'],
            'channel' => 'web',
            'channel_data' => ['user_agent' => 'Mozilla/5.0', 'ip' => '192.168.1.1'],
            'has_checkout' => true,
            'buyer_uid' => 'buy_123456789',
            'profile_uid' => 'pro_123456789',
            'livemode' => false,
            'object' => 'transaction',
            'created' => 1705315800,
            'updated' => 1705319400,
            'completed' => 1705319500,
            'metadata' => ['order_id' => 'ORD-123', 'customer_type' => 'premium'],
            'statuses' => [
                ['status' => 'created', 'timestamp' => 1705315800],
                ['status' => 'completed', 'timestamp' => 1705319500],
            ],
            'order' => ['reference' => 'ORD-123', 'description' => 'Premium subscription'],
            'fees' => ['processing' => 50, 'platform' => 25],
            'refunds' => [],
        ]);

        expect($data->uid)->toBe('tra_123456789');
        expect($data->status)->toBe('completed');
        expect($data->merchant_uid)->toBe('mer_123456789');
        expect($data->amount)->toBe(2500);
        expect($data->currency)->toBe('EUR');
        expect($data->redirect_url)->toBe('https://payment.example.com/pay/tra_123456789');
        expect($data->return_url)->toBe('https://store.example.com/success');
        expect($data->notify_url)->toBe('https://store.example.com/notify');
        expect($data->payment_method)->toBe('ideal');
        expect($data->payment_flow)->toBe('hosted');
        expect($data->payment_details)->toBe(['bank' => 'ABNANL2A', 'account' => '****1234']);
        expect($data->channel)->toBe('web');
        expect($data->channel_data)->toBe(['user_agent' => 'Mozilla/5.0', 'ip' => '192.168.1.1']);
        expect($data->has_checkout)->toBe(true);
        expect($data->buyer_uid)->toBe('buy_123456789');
        expect($data->profile_uid)->toBe('pro_123456789');
        expect($data->livemode)->toBe(false);
        expect($data->object)->toBe('transaction');
        expect($data->created)->toBe(1705315800);
        expect($data->updated)->toBe(1705319400);
        expect($data->completed)->toBe(1705319500);
        expect($data->metadata)->toBe(['order_id' => 'ORD-123', 'customer_type' => 'premium']);
        expect($data->statuses)->toBe([
            ['status' => 'created', 'timestamp' => 1705315800],
            ['status' => 'completed', 'timestamp' => 1705319500],
        ]);
        expect($data->order)->toBe(['reference' => 'ORD-123', 'description' => 'Premium subscription']);
        expect($data->fees)->toBe(['processing' => 50, 'platform' => 25]);
        expect($data->refunds)->toBe([]);
    });

    test('it can create TransactionData with minimal required data', function () {
        $data = new TransactionData(
            uid: 'tra_987654321',
            status: 'pending',
            merchant_uid: 'mer_987654321',
            amount: 1000,
            currency: 'EUR'
        );

        expect($data->uid)->toBe('tra_987654321');
        expect($data->status)->toBe('pending');
        expect($data->merchant_uid)->toBe('mer_987654321');
        expect($data->amount)->toBe(1000);
        expect($data->currency)->toBe('EUR');
        expect($data->redirect_url)->toBeNull();
        expect($data->return_url)->toBeNull();
        expect($data->notify_url)->toBeNull();
        expect($data->payment_method)->toBeNull();
        expect($data->payment_flow)->toBeNull();
        expect($data->payment_details)->toBeNull();
        expect($data->channel)->toBeNull();
        expect($data->channel_data)->toBeNull();
        expect($data->has_checkout)->toBeNull();
        expect($data->buyer_uid)->toBeNull();
        expect($data->profile_uid)->toBeNull();
        expect($data->livemode)->toBeNull();
        expect($data->object)->toBeNull();
        expect($data->created)->toBeNull();
        expect($data->updated)->toBeNull();
        expect($data->completed)->toBeNull();
        expect($data->metadata)->toBeNull();
        expect($data->statuses)->toBeNull();
        expect($data->order)->toBeNull();
        expect($data->escrow)->toBeNull();
        expect($data->fees)->toBeNull();
        expect($data->refunds)->toBeNull();
    });

    test('it can handle failed transaction data', function () {
        $data = TransactionData::from([
            'uid' => 'tra_failed_123',
            'status' => 'failed',
            'merchant_uid' => 'mer_123456789',
            'amount' => 1500,
            'currency' => 'EUR',
            'payment_method' => 'credit_card',
            'created' => 1705315800,
            'updated' => 1705316000,
            'statuses' => [
                ['status' => 'created', 'timestamp' => 1705315800],
                ['status' => 'failed', 'timestamp' => 1705316000],
            ],
        ]);

        expect($data->uid)->toBe('tra_failed_123');
        expect($data->status)->toBe('failed');
        expect($data->amount)->toBe(1500);
        expect($data->payment_method)->toBe('credit_card');
        expect($data->completed)->toBeNull();
    });
});
