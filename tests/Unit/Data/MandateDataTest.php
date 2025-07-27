<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Mandates\MandateData;

describe('MandateData', function () {
    test('it can create MandateData from array', function () {
        $data = MandateData::from([
            'uid' => 'man_123456789',
            'status' => 'active',
            'merchant_uid' => 'mer_123456789',
            'mandate_url' => 'https://api.example.com/mandates/man_123456789',
            'description' => 'Monthly subscription mandate',
            'reference' => 'REF-2024-001',
            'metadata' => ['subscription_id' => 'sub_123'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
            'signed_at' => '2024-01-15T11:00:00Z',
            'cancelled_at' => null,
            'iban' => 'NL91ABNA0417164300',
            'bic' => 'ABNANL2A',
            'holder_name' => 'John Doe',
        ]);

        expect($data->uid)->toBe('man_123456789');
        expect($data->status)->toBe('active');
        expect($data->merchant_uid)->toBe('mer_123456789');
        expect($data->mandate_url)->toBe('https://api.example.com/mandates/man_123456789');
        expect($data->description)->toBe('Monthly subscription mandate');
        expect($data->reference)->toBe('REF-2024-001');
        expect($data->metadata)->toBe(['subscription_id' => 'sub_123']);
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->signed_at)->toBe('2024-01-15T11:00:00Z');
        expect($data->cancelled_at)->toBeNull();
        expect($data->iban)->toBe('NL91ABNA0417164300');
        expect($data->bic)->toBe('ABNANL2A');
        expect($data->holder_name)->toBe('John Doe');
    });

    test('it can create MandateData with minimal required data', function () {
        $data = new MandateData(
            uid: 'man_987654321',
            status: 'pending',
            merchant_uid: 'mer_987654321',
            mandate_url: 'https://api.example.com/mandates/man_987654321'
        );

        expect($data->uid)->toBe('man_987654321');
        expect($data->status)->toBe('pending');
        expect($data->merchant_uid)->toBe('mer_987654321');
        expect($data->mandate_url)->toBe('https://api.example.com/mandates/man_987654321');
        expect($data->description)->toBeNull();
        expect($data->reference)->toBeNull();
        expect($data->metadata)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
        expect($data->signed_at)->toBeNull();
        expect($data->cancelled_at)->toBeNull();
        expect($data->iban)->toBeNull();
        expect($data->bic)->toBeNull();
        expect($data->holder_name)->toBeNull();
    });

    test('it can handle cancelled mandate data', function () {
        $data = MandateData::from([
            'uid' => 'man_cancelled_123',
            'status' => 'cancelled',
            'merchant_uid' => 'mer_123456789',
            'mandate_url' => 'https://api.example.com/mandates/man_cancelled_123',
            'created_at' => '2024-01-15T10:30:00Z',
            'cancelled_at' => '2024-01-20T15:45:00Z',
        ]);

        expect($data->uid)->toBe('man_cancelled_123');
        expect($data->status)->toBe('cancelled');
        expect($data->cancelled_at)->toBe('2024-01-20T15:45:00Z');
    });
});