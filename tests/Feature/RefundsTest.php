<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateRefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\CreateRefundRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\GetTransactionRefundsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector('test-api-key');
    $this->transactionUid = 'txn_test123';
});

it('can create a refund for a transaction', function () {
    $mockClient = new MockClient([
        CreateRefundRequest::class => MockResponse::make([
            'uid' => 'ref_test123',
            'status' => 'created',
            'amount' => 1000,
            'currency' => 'EUR',
            'payout_description' => 'Refund for defective product',
            'message' => 'product_defect',
            'created_at' => '2024-01-01T10:00:00Z',
            'updated_at' => '2024-01-01T10:00:00Z',
            'processed_at' => null,
            'internal_reason' => 'product_defect',
            'metadata' => ['reason' => 'defective'],
            'livemode' => false,
            'object' => 'refund',
        ], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $refundData = new CreateRefundData(
        amount: 1000,
        payout_description: 'Refund for defective product',
        internal_reason: 'product_defect',
        metadata: ['reason' => 'defective']
    );

    $response = $this->connector->transactions()->refunds($this->transactionUid)->create($refundData);
    $refund = $response->dto();

    expect($refund)->toBeInstanceOf(RefundData::class);
    expect($refund->uid)->toBe('ref_test123');
    expect($refund->status)->toBe('created');
    expect($refund->amount)->toBe(1000);
    expect($refund->currency)->toBe('EUR');
    expect($refund->payout_description)->toBe('Refund for defective product');
    expect($refund->message)->toBe('product_defect');
    expect($refund->internal_reason)->toBe('product_defect');
    expect($refund->metadata)->toBe(['reason' => 'defective']);

    $mockClient->assertSent(CreateRefundRequest::class);
});

it('can list refunds for a transaction', function () {
    $mockClient = new MockClient([
        GetTransactionRefundsRequest::class => MockResponse::make([
            'object' => 'list',
            'data' => [
                [
                    'uid' => 'ref_test123',
                    'status' => 'completed',
                    'amount' => 1000,
                    'currency' => 'EUR',
                    'payout_description' => 'Refund for defective product',
                    'message' => 'product_defect',
                    'created_at' => '2024-01-01T10:00:00Z',
                    'updated_at' => '2024-01-01T10:00:00Z',
                    'processed_at' => '2024-01-01T10:05:00Z',
                    'internal_reason' => 'product_defect',
                    'metadata' => ['reason' => 'defective'],
                    'livemode' => false,
                    'object' => 'refund',
                ],
                [
                    'uid' => 'ref_test456',
                    'status' => 'created',
                    'amount' => 500,
                    'currency' => 'EUR',
                    'payout_description' => 'Partial refund',
                    'message' => 'customer_request',
                    'created_at' => '2024-01-01T10:10:00Z',
                    'updated_at' => '2024-01-01T10:10:00Z',
                    'processed_at' => null,
                    'internal_reason' => 'customer_request',
                    'metadata' => [],
                    'livemode' => false,
                    'object' => 'refund',
                ],
            ],
            'has_more' => false,
            'total_item_count' => 2,
            'items_per_page' => 10,
            'current_page' => 1,
            'last_page' => 1,
        ], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->transactions()->refunds($this->transactionUid)->list();
    $refunds = $response->dto();

    expect($refunds->data)->toHaveCount(2);
    expect($refunds->data[0])->toBeInstanceOf(RefundData::class);
    expect($refunds->data[0]->uid)->toBe('ref_test123');
    expect($refunds->data[0]->status)->toBe('completed');
    expect($refunds->data[1]->uid)->toBe('ref_test456');
    expect($refunds->data[1]->status)->toBe('created');

    $mockClient->assertSent(GetTransactionRefundsRequest::class);
});

it('can create refund with array data for backward compatibility', function () {
    $mockClient = new MockClient([
        CreateRefundRequest::class => MockResponse::make([
            'uid' => 'ref_test789',
            'status' => 'created',
            'amount' => 2500,
            'currency' => 'EUR',
            'payout_description' => 'Order cancellation',
            'message' => 'cancellation',
            'created_at' => '2024-01-01T10:15:00Z',
            'updated_at' => '2024-01-01T10:15:00Z',
            'processed_at' => null,
            'internal_reason' => 'cancellation',
            'metadata' => [],
            'livemode' => false,
            'object' => 'refund',
        ], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->transactions()->refunds($this->transactionUid)->create([
        'amount' => 2500,
        'payout_description' => 'Order cancellation',
        'internal_reason' => 'cancellation',
    ]);

    $refund = $response->dto();
    expect($refund->uid)->toBe('ref_test789');
    expect($refund->amount)->toBe(2500);

    $mockClient->assertSent(CreateRefundRequest::class);
});

it('handles refund creation errors properly', function () {
    $mockClient = new MockClient([
        CreateRefundRequest::class => MockResponse::make([
            'error' => [
                'message' => 'Refund amount exceeds transaction amount',
                'type' => 'invalid_request_error',
            ],
        ], 400),
    ]);

    $this->connector->withMockClient($mockClient);

    $refundData = new CreateRefundData(
        amount: 10000,
        payout_description: 'Invalid refund amount'
    );

    $response = $this->connector->transactions()->refunds($this->transactionUid)->create($refundData);

    expect($response->status())->toBe(400);
    expect($response->json('error.message'))->toBe('Refund amount exceeds transaction amount');

    $mockClient->assertSent(CreateRefundRequest::class);
});
