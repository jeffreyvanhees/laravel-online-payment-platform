<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Files\CreateFileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Mandates\CreateMandateData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateRefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Files\FileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Mandates\MandateData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements\SettlementRowData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;
use Spatie\LaravelData\DataCollection;

it('can create CreateRefundData with all fields', function () {
    $refundData = new CreateRefundData(
        amount: 1000,
        payout_description: 'Product defect refund',
        internal_reason: 'defective_product',
        metadata: ['reason' => 'manufacturing_defect']
    );

    expect($refundData->amount)->toBe(1000);
    expect($refundData->payout_description)->toBe('Product defect refund');
    expect($refundData->internal_reason)->toBe('defective_product');
    expect($refundData->metadata)->toBe(['reason' => 'manufacturing_defect']);
    
    $array = $refundData->toArray();
    expect($array)->toHaveKey('amount', 1000);
    expect($array)->toHaveKey('payout_description', 'Product defect refund');
    expect($array)->toHaveKey('internal_reason', 'defective_product');
    expect($array)->toHaveKey('metadata', ['reason' => 'manufacturing_defect']);
});

it('can create RefundData from API response', function () {
    $responseData = [
        'uid' => 'ref_test123',
        'status' => 'completed',
        'amount' => 1500,
        'payout_description' => 'Customer return',
        'internal_reason' => 'return',
        'created' => 1640995200,
        'updated' => 1640995300,
        'paid' => 1640995400,
        'fees' => ['processing' => 15],
        'metadata' => ['return_reason' => 'size_issue'],
        'livemode' => false,
        'object' => 'refund',
    ];

    $refund = RefundData::from($responseData);

    expect($refund->uid)->toBe('ref_test123');
    expect($refund->status)->toBe('completed');
    expect($refund->amount)->toBe(1500);
    expect($refund->payout_description)->toBe('Customer return');
    expect($refund->internal_reason)->toBe('return');
    expect($refund->fees)->toBe(['processing' => 15]);
    expect($refund->metadata)->toBe(['return_reason' => 'size_issue']);
});

it('can create CreateMandateData with product collection', function () {
    $products = DataCollection::from([
        new ProductData(
            name: 'Subscription Plan',
            quantity: 1,
            price: 2500,
            description: 'Monthly subscription',
            vat_rate: '21'
        ),
        new ProductData(
            name: 'Setup Fee',
            quantity: 1,
            price: 500,
            description: 'One-time setup fee'
        )
    ], ProductData::class);

    $mandateData = new CreateMandateData(
        merchant_uid: 'mer_test123',
        mandate_method: 'payment',
        mandate_type: 'consumer',
        mandate_repeat: 'subscription',
        mandate_amount: 100,
        products: $products,
        total_price: 3000,
        return_url: 'https://example.com/return',
        notify_url: 'https://example.com/notify',
        payment_method: 'ideal',
        description: 'Monthly subscription mandate'
    );

    expect($mandateData->merchant_uid)->toBe('mer_test123');
    expect($mandateData->mandate_method)->toBe('payment');
    expect($mandateData->mandate_type)->toBe('consumer');
    expect($mandateData->products)->toBeInstanceOf(DataCollection::class);
    expect($mandateData->products)->toHaveCount(2);
    expect($mandateData->total_price)->toBe(3000);
    expect($mandateData->payment_method)->toBe('ideal');

    $array = $mandateData->toArray();
    expect($array)->toHaveKey('merchant_uid', 'mer_test123');
    expect($array)->toHaveKey('products');
    expect($array['products'])->toHaveCount(2);
    expect($array['products'][0])->toHaveKey('name', 'Subscription Plan');
});

it('can create MandateData from API response', function () {
    $responseData = [
        'uid' => 'man_test123',
        'status' => 'created',
        'mandate_method' => 'payment',
        'mandate_type' => 'consumer',
        'mandate_repeat' => 'subscription',
        'amount' => 100,
        'redirect_url' => 'https://sandbox.onlinebetaalplatform.nl/mandate/123',
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
        'created' => 1640995200,
        'updated' => 1640995200,
        'completed' => null,
        'expired' => 1640999800,
        'customer' => ['email' => 'test@example.com'],
        'order' => ['reference' => 'ORD-123'],
        'metadata' => ['subscription_type' => 'premium'],
        'livemode' => false,
        'object' => 'mandate',
    ];

    $mandate = MandateData::from($responseData);

    expect($mandate->uid)->toBe('man_test123');
    expect($mandate->status)->toBe('created');
    expect($mandate->mandate_method)->toBe('payment');
    expect($mandate->mandate_type)->toBe('consumer');
    expect($mandate->amount)->toBe(100);
    expect($mandate->redirect_url)->toBe('https://sandbox.onlinebetaalplatform.nl/mandate/123');
    expect($mandate->customer)->toBe(['email' => 'test@example.com']);
    expect($mandate->metadata)->toBe(['subscription_type' => 'premium']);
});

it('can create CreateFileData for file uploads', function () {
    $fileData = new CreateFileData(
        purpose: 'organization_structure',
        merchant_uid: 'mer_test123',
        object_uid: 'mer_test123',
        metadata: ['document_type' => 'articles_of_incorporation']
    );

    expect($fileData->purpose)->toBe('organization_structure');
    expect($fileData->merchant_uid)->toBe('mer_test123');
    expect($fileData->object_uid)->toBe('mer_test123');
    expect($fileData->metadata)->toBe(['document_type' => 'articles_of_incorporation']);

    $array = $fileData->toArray();
    expect($array)->toHaveKey('purpose', 'organization_structure');
    expect($array)->toHaveKey('merchant_uid', 'mer_test123');
    expect($array)->not->toHaveKey('null_field'); // null filtering works
});

it('can create FileData from API response', function () {
    $responseData = [
        'uid' => 'file_test123',
        'purpose' => 'coc_extract',
        'merchant_uid' => 'mer_test123',
        'object_uid' => 'mer_test123',
        'token' => 'upload_token_xyz',
        'url' => 'https://files-sandbox.onlinebetaalplatform.nl/v1/uploads/file_test123',
        'created' => 1640995200,
        'updated' => 1640995200,
        'expired' => 1640999800,
        'metadata' => ['original_filename' => 'coc_extract.pdf'],
    ];

    $file = FileData::from($responseData);

    expect($file->uid)->toBe('file_test123');
    expect($file->purpose)->toBe('coc_extract');
    expect($file->token)->toBe('upload_token_xyz');
    expect($file->url)->toBe('https://files-sandbox.onlinebetaalplatform.nl/v1/uploads/file_test123');
    expect($file->metadata)->toBe(['original_filename' => 'coc_extract.pdf']);
});

it('can create SettlementRowData from API response', function () {
    $responseData = [
        'type' => 'transaction',
        'reference' => 'txn_abc123',
        'total_partner_fee' => 150,
        'amount' => 5000,
        'amount_payable' => 4850,
        'metadata' => ['merchant_uid' => 'mer_test123', 'product_type' => 'digital'],
    ];

    $row = SettlementRowData::from($responseData);

    expect($row->type)->toBe('transaction');
    expect($row->reference)->toBe('txn_abc123');
    expect($row->total_partner_fee)->toBe(150);
    expect($row->amount)->toBe(5000);
    expect($row->amount_payable)->toBe(4850);
    expect($row->metadata)->toBe(['merchant_uid' => 'mer_test123', 'product_type' => 'digital']);
});

it('filters null values from DTO arrays', function () {
    $refundData = new CreateRefundData(
        amount: 1000,
        payout_description: 'Test refund',
        internal_reason: null, // This should be filtered out
        metadata: null // This should be filtered out
    );

    $array = $refundData->toArray();
    
    expect($array)->toHaveKey('amount', 1000);
    expect($array)->toHaveKey('payout_description', 'Test refund');
    expect($array)->not->toHaveKey('internal_reason');
    expect($array)->not->toHaveKey('metadata');
});