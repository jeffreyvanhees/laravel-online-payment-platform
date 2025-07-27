<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;

describe('MerchantData', function () {
    test('it can create MerchantData from array', function () {
        $data = MerchantData::from([
            'uid' => 'mer_123456789',
            'type' => 'business',
            'status' => 'active',
            'country' => 'NLD',
            'emailaddress' => 'merchant@example.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'company_name' => 'Example Company',
            'coc_nr' => '12345678',
            'vat_nr' => 'NL123456789B01',
            'legal_name' => 'Example Company B.V.',
            'trading_names' => ['Example Store', 'Example Shop'],
            'phone_number' => '+31612345678',
            'phone' => '+31201234567',
            'language' => 'en',
            'reference' => 'REF-123',
            'notify_url' => 'https://example.com/notify',
            'return_url' => 'https://example.com/return',
            'metadata' => ['segment' => 'retail', 'tier' => 'premium'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
        ]);

        expect($data->uid)->toBe('mer_123456789');
        expect($data->type)->toBe('business');
        expect($data->status)->toBe('active');
        expect($data->country)->toBe('NLD');
        expect($data->emailaddress)->toBe('merchant@example.com');
        expect($data->first_name)->toBe('John');
        expect($data->last_name)->toBe('Doe');
        expect($data->company_name)->toBe('Example Company');
        expect($data->coc_nr)->toBe('12345678');
        expect($data->vat_nr)->toBe('NL123456789B01');
        expect($data->legal_name)->toBe('Example Company B.V.');
        expect($data->trading_names)->toBe(['Example Store', 'Example Shop']);
        expect($data->phone_number)->toBe('+31612345678');
        expect($data->phone)->toBe('+31201234567');
        expect($data->language)->toBe('en');
        expect($data->reference)->toBe('REF-123');
        expect($data->notify_url)->toBe('https://example.com/notify');
        expect($data->return_url)->toBe('https://example.com/return');
        expect($data->metadata)->toBe(['segment' => 'retail', 'tier' => 'premium']);
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
    });

    test('it can create MerchantData with minimal required data', function () {
        $data = new MerchantData(
            uid: 'mer_987654321',
            type: 'individual',
            status: 'pending',
            country: 'NLD'
        );

        expect($data->uid)->toBe('mer_987654321');
        expect($data->type)->toBe('individual');
        expect($data->status)->toBe('pending');
        expect($data->country)->toBe('NLD');
        expect($data->emailaddress)->toBeNull();
        expect($data->first_name)->toBeNull();
        expect($data->last_name)->toBeNull();
        expect($data->company_name)->toBeNull();
        expect($data->coc_nr)->toBeNull();
        expect($data->vat_nr)->toBeNull();
        expect($data->legal_name)->toBeNull();
        expect($data->trading_names)->toBeNull();
        expect($data->phone_number)->toBeNull();
        expect($data->phone)->toBeNull();
        expect($data->language)->toBeNull();
        expect($data->reference)->toBeNull();
        expect($data->notify_url)->toBeNull();
        expect($data->return_url)->toBeNull();
        expect($data->metadata)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
        expect($data->addresses)->toBeNull();
        expect($data->contacts)->toBeNull();
        expect($data->bank_accounts)->toBeNull();
        expect($data->ubos)->toBeNull();
    });

    test('it can handle suspended merchant data', function () {
        $data = MerchantData::from([
            'uid' => 'mer_suspended_123',
            'type' => 'business',
            'status' => 'suspended',
            'country' => 'NLD',
            'company_name' => 'Suspended Company',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-20T15:45:00Z',
        ]);

        expect($data->uid)->toBe('mer_suspended_123');
        expect($data->type)->toBe('business');
        expect($data->status)->toBe('suspended');
        expect($data->company_name)->toBe('Suspended Company');
        expect($data->updated_at)->toBe('2024-01-20T15:45:00Z');
    });
});
