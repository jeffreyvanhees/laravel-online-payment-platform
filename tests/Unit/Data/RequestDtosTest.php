<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges\CreateChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Disputes\CreateDisputeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Files\CreateFileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Mandates\CreateMandateData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateMerchantProfileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateMerchantUBOData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\MigrateMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\BuyerData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateRefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\EscrowData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\UpdateEscrowDateData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Withdrawals\CreateWithdrawalData;

describe('Request DTOs', function () {
    test('it can create CreateChargeData DTO', function () {
        $data = new CreateChargeData(
            type: 'balance',
            amount: 1000,
            from_owner_uid: 'mer_123456789',
            to_owner_uid: 'mer_987654321',
            description: 'Test charge',
            metadata: ['key' => 'value']
        );

        expect($data->type)->toBe('balance');
        expect($data->amount)->toBe(1000);
        expect($data->from_owner_uid)->toBe('mer_123456789');
        expect($data->to_owner_uid)->toBe('mer_987654321');
        expect($data->description)->toBe('Test charge');
        expect($data->metadata)->toBe(['key' => 'value']);
    });

    test('it can create CreateDisputeData DTO', function () {
        $data = new CreateDisputeData(
            transaction_uid: 'tra_123456789',
            reason: 'fraudulent',
            message: 'Fraudulent transaction',
            evidence_file_uids: ['fil_123', 'fil_456']
        );

        expect($data->transaction_uid)->toBe('tra_123456789');
        expect($data->reason)->toBe('fraudulent');
        expect($data->message)->toBe('Fraudulent transaction');
        expect($data->evidence_file_uids)->toBe(['fil_123', 'fil_456']);
    });

    test('it can create CreateFileData DTO', function () {
        $data = new CreateFileData(
            purpose: 'identity_document',
            file_path: '/path/to/document.pdf',
            description: 'Identity verification document'
        );

        expect($data->purpose)->toBe('identity_document');
        expect($data->file_path)->toBe('/path/to/document.pdf');
        expect($data->description)->toBe('Identity verification document');
    });

    test('it can create CreateMandateData DTO', function () {
        $data = new CreateMandateData(
            merchant_uid: 'mer_123456789',
            return_url: 'https://example.com/return',
            notify_url: 'https://example.com/notify',
            description: 'Test mandate',
            reference: 'REF123',
            metadata: ['custom' => 'value']
        );

        expect($data->merchant_uid)->toBe('mer_123456789');
        expect($data->return_url)->toBe('https://example.com/return');
        expect($data->notify_url)->toBe('https://example.com/notify');
        expect($data->description)->toBe('Test mandate');
        expect($data->reference)->toBe('REF123');
        expect($data->metadata)->toBe(['custom' => 'value']);
    });

    test('it can create CreateMerchantProfileData DTO', function () {
        $data = new CreateMerchantProfileData(
            name: 'E-commerce Profile',
            description: 'Settings for online store',
            webhook_url: 'https://store.example.com/webhook',
            return_url: 'https://store.example.com/success',
            is_default: false
        );

        expect($data->name)->toBe('E-commerce Profile');
        expect($data->description)->toBe('Settings for online store');
        expect($data->webhook_url)->toBe('https://store.example.com/webhook');
        expect($data->return_url)->toBe('https://store.example.com/success');
        expect($data->is_default)->toBe(false);
    });

    test('it can create CreateMerchantUBOData DTO', function () {
        $data = new CreateMerchantUBOData(
            name_first: 'John',
            name_last: 'Doe',
            date_of_birth: '1980-01-15',
            country_of_residence: 'NLD',
            is_decision_maker: true,
            percentage_of_shares: 25.5
        );

        expect($data->name_first)->toBe('John');
        expect($data->name_last)->toBe('Doe');
        expect($data->date_of_birth)->toBe('1980-01-15');
        expect($data->country_of_residence)->toBe('NLD');
        expect($data->is_decision_maker)->toBe(true);
        expect($data->percentage_of_shares)->toBe(25.5);
    });

    test('it can create MigrateMerchantData DTO', function () {
        $data = new MigrateMerchantData(
            coc_nr: '12345678',
            country: 'NLD'
        );

        expect($data->coc_nr)->toBe('12345678');
        expect($data->country)->toBe('NLD');
    });

    test('it can create BuyerData DTO', function () {
        $data = new BuyerData(
            emailaddress: 'buyer@example.com',
            first_name: 'Jane',
            last_name: 'Smith',
            phone_number: '+31612345678'
        );

        expect($data->emailaddress)->toBe('buyer@example.com');
        expect($data->first_name)->toBe('Jane');
        expect($data->last_name)->toBe('Smith');
        expect($data->phone_number)->toBe('+31612345678');
    });

    test('it can create CreateRefundData DTO', function () {
        $data = new CreateRefundData(
            amount: 1000,
            payout_description: 'Refund for order #123',
            message: 'Customer requested refund'
        );

        expect($data->amount)->toBe(1000);
        expect($data->payout_description)->toBe('Refund for order #123');
        expect($data->message)->toBe('Customer requested refund');
    });

    test('it can create EscrowData DTO', function () {
        $data = new EscrowData(
            enabled: true,
            release_date: '2024-12-31'
        );

        expect($data->enabled)->toBe(true);
        expect($data->release_date)->toBe('2024-12-31');
    });

    test('it can create UpdateEscrowDateData DTO', function () {
        $data = new UpdateEscrowDateData(
            escrow_date: '2024-12-31'
        );

        expect($data->escrow_date)->toBe('2024-12-31');
    });

    test('it can create CreateWithdrawalData DTO', function () {
        $data = new CreateWithdrawalData(
            merchant_uid: 'mer_123456789',
            amount: 5000,
            bank_account_uid: 'ban_123456789',
            description: 'Monthly withdrawal',
            reference: 'REF123',
            metadata: ['key' => 'value']
        );

        expect($data->merchant_uid)->toBe('mer_123456789');
        expect($data->amount)->toBe(5000);
        expect($data->bank_account_uid)->toBe('ban_123456789');
        expect($data->description)->toBe('Monthly withdrawal');
        expect($data->reference)->toBe('REF123');
        expect($data->metadata)->toBe(['key' => 'value']);
    });
});
