<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Charges\ChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Disputes\DisputeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Files\FileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\UBOData;

describe('Response DTOs', function () {
    test('it can create ChargeData from array', function () {
        $data = ChargeData::from([
            'uid' => 'cha_123456789',
            'type' => 'balance',
            'status' => 'completed',
            'amount' => 1000,
            'currency' => 'EUR',
            'from_owner_uid' => 'mer_123456789',
            'to_owner_uid' => 'mer_987654321',
            'description' => 'Test charge',
            'metadata' => ['key' => 'value'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
        ]);

        expect($data->uid)->toBe('cha_123456789');
        expect($data->type)->toBe('balance');
        expect($data->status)->toBe('completed');
        expect($data->amount)->toBe(1000);
        expect($data->currency)->toBe('EUR');
        expect($data->from_owner_uid)->toBe('mer_123456789');
        expect($data->to_owner_uid)->toBe('mer_987654321');
        expect($data->description)->toBe('Test charge');
        expect($data->metadata)->toBe(['key' => 'value']);
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
    });

    test('it can create DisputeData from array', function () {
        $data = DisputeData::from([
            'uid' => 'dis_123456789',
            'status' => 'open',
            'transaction_uid' => 'tra_123456789',
            'reason' => 'fraudulent',
            'message' => 'Fraudulent transaction',
            'evidence_file_uids' => ['fil_123', 'fil_456'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
            'resolved_at' => null,
        ]);

        expect($data->uid)->toBe('dis_123456789');
        expect($data->status)->toBe('open');
        expect($data->transaction_uid)->toBe('tra_123456789');
        expect($data->reason)->toBe('fraudulent');
        expect($data->message)->toBe('Fraudulent transaction');
        expect($data->evidence_file_uids)->toBe(['fil_123', 'fil_456']);
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->resolved_at)->toBeNull();
    });

    test('it can create basic response data structures', function () {
        // Test that we can create basic response objects
        $chargeData = new ChargeData(
            uid: 'cha_123',
            type: 'balance',
            status: 'completed',
            amount: 1000,
            currency: 'EUR',
            from_owner_uid: 'mer_123',
            to_owner_uid: 'mer_456'
        );

        expect($chargeData->uid)->toBe('cha_123');
        expect($chargeData->amount)->toBe(1000);
    });

    test('it can create ChargeData with minimal data', function () {
        $data = new ChargeData(
            uid: 'cha_123456789',
            type: 'balance',
            status: 'pending',
            amount: 500,
            currency: 'EUR',
            from_owner_uid: 'mer_123456789',
            to_owner_uid: 'mer_987654321'
        );

        expect($data->uid)->toBe('cha_123456789');
        expect($data->type)->toBe('balance');
        expect($data->status)->toBe('pending');
        expect($data->amount)->toBe(500);
        expect($data->currency)->toBe('EUR');
        expect($data->from_owner_uid)->toBe('mer_123456789');
        expect($data->to_owner_uid)->toBe('mer_987654321');
        expect($data->description)->toBeNull();
        expect($data->metadata)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
    });

    test('it can create DisputeData with minimal data', function () {
        $data = new DisputeData(
            uid: 'dis_123456789',
            status: 'open',
            transaction_uid: 'tra_123456789',
            reason: 'fraudulent'
        );

        expect($data->uid)->toBe('dis_123456789');
        expect($data->status)->toBe('open');
        expect($data->transaction_uid)->toBe('tra_123456789');
        expect($data->reason)->toBe('fraudulent');
        expect($data->message)->toBeNull();
        expect($data->evidence_file_uids)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
        expect($data->resolved_at)->toBeNull();
    });

    test('it can create FileData from array', function () {
        $data = FileData::from([
            'uid' => 'fil_123456789',
            'purpose' => 'identity_document',
            'filename' => 'document.pdf',
            'size' => 1024,
            'mime_type' => 'application/pdf',
            'url' => 'https://files.example.com/fil_123456789.pdf',
            'description' => 'Identity verification document',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
        ]);

        expect($data->uid)->toBe('fil_123456789');
        expect($data->purpose)->toBe('identity_document');
        expect($data->filename)->toBe('document.pdf');
        expect($data->size)->toBe(1024);
        expect($data->mime_type)->toBe('application/pdf');
        expect($data->url)->toBe('https://files.example.com/fil_123456789.pdf');
        expect($data->description)->toBe('Identity verification document');
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
    });

    test('it can create UBOData from array', function () {
        $data = UBOData::from([
            'uid' => 'ubo_123456789',
            'name_first' => 'John',
            'name_last' => 'Doe',
            'date_of_birth' => '1980-01-15',
            'country_of_residence' => 'NLD',
            'name_prefix' => 'van',
            'is_decision_maker' => true,
            'is_pep' => false,
            'percentage_of_shares' => 25.5,
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
        ]);

        expect($data->uid)->toBe('ubo_123456789');
        expect($data->name_first)->toBe('John');
        expect($data->name_last)->toBe('Doe');
        expect($data->date_of_birth)->toBe('1980-01-15');
        expect($data->country_of_residence)->toBe('NLD');
        expect($data->name_prefix)->toBe('van');
        expect($data->is_decision_maker)->toBe(true);
        expect($data->is_pep)->toBe(false);
        expect($data->percentage_of_shares)->toBe(25.5);
        expect($data->created_at)->toBe('2024-01-15T10:30:00Z');
        expect($data->updated_at)->toBe('2024-01-15T10:30:00Z');
    });

    test('it can create FileData with minimal data', function () {
        $data = new FileData(
            uid: 'fil_123456789',
            purpose: 'identity_document',
            filename: 'document.pdf',
            size: 1024,
            mime_type: 'application/pdf',
            url: 'https://files.example.com/fil_123456789.pdf'
        );

        expect($data->uid)->toBe('fil_123456789');
        expect($data->purpose)->toBe('identity_document');
        expect($data->filename)->toBe('document.pdf');
        expect($data->size)->toBe(1024);
        expect($data->mime_type)->toBe('application/pdf');
        expect($data->url)->toBe('https://files.example.com/fil_123456789.pdf');
        expect($data->description)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
    });

    test('it can create UBOData with minimal data', function () {
        $data = new UBOData(
            uid: 'ubo_123456789',
            name_first: 'John',
            name_last: 'Doe',
            date_of_birth: '1980-01-15',
            country_of_residence: 'NLD'
        );

        expect($data->uid)->toBe('ubo_123456789');
        expect($data->name_first)->toBe('John');
        expect($data->name_last)->toBe('Doe');
        expect($data->date_of_birth)->toBe('1980-01-15');
        expect($data->country_of_residence)->toBe('NLD');
        expect($data->name_prefix)->toBeNull();
        expect($data->is_decision_maker)->toBeNull();
        expect($data->is_pep)->toBeNull();
        expect($data->percentage_of_shares)->toBeNull();
        expect($data->created_at)->toBeNull();
        expect($data->updated_at)->toBeNull();
    });
});
