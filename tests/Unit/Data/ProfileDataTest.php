<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\ProfileData;

describe('ProfileData', function () {
    test('it can create ProfileData from array', function () {
        $data = ProfileData::from([
            'uid' => 'pro_123456789',
            'name' => 'E-commerce Profile',
            'description' => 'Main profile for online store',
            'settings' => ['currency' => 'EUR', 'language' => 'en'],
            'webhook_url' => 'https://store.example.com/webhook',
            'return_url' => 'https://store.example.com/return',
            'is_default' => true,
            'status' => 'active',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
        ]);

        expect($data->uid)->toBe('pro_123456789');
        expect($data->name)->toBe('E-commerce Profile');
        expect($data->description)->toBe('Main profile for online store');
        expect($data->settings)->toBe(['currency' => 'EUR', 'language' => 'en']);
        expect($data->webhook_url)->toBe('https://store.example.com/webhook');
        expect($data->return_url)->toBe('https://store.example.com/return');
        expect($data->is_default)->toBe(true);
        expect($data->status)->toBe('active');
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
    });

    test('it can create ProfileData with minimal required data', function () {
        $data = new ProfileData(
            uid: 'pro_987654321',
            name: 'Test Profile'
        );

        expect($data->uid)->toBe('pro_987654321');
        expect($data->name)->toBe('Test Profile');
        expect($data->description)->toBeNull();
        expect($data->settings)->toBeNull();
        expect($data->webhook_url)->toBeNull();
        expect($data->return_url)->toBeNull();
        expect($data->is_default)->toBeNull();
        expect($data->status)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
    });

    test('it can handle inactive profile data', function () {
        $data = ProfileData::from([
            'uid' => 'pro_inactive_123',
            'name' => 'Inactive Profile',
            'status' => 'inactive',
            'is_default' => false,
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-20T15:45:00Z',
        ]);

        expect($data->uid)->toBe('pro_inactive_123');
        expect($data->name)->toBe('Inactive Profile');
        expect($data->status)->toBe('inactive');
        expect($data->is_default)->toBe(false);
        expect($data->updated_at)->toBe('2024-01-20T15:45:00Z');
    });
});
