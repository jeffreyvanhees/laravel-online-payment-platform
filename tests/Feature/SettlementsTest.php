<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements\SettlementData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Settlements\SettlementRowData;
use JeffreyVanHees\OnlinePaymentPlatform\OnlinePaymentPlatformConnector;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements\GetSettlementsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements\GetSettlementSpecificationRowsRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

beforeEach(function () {
    $this->connector = new OnlinePaymentPlatformConnector('test-api-key');
});

it('can list all settlements', function () {
    $mockClient = new MockClient([
        GetSettlementsRequest::class => MockResponse::make([
            'object' => 'list',
            'data' => [
                [
                    'uid' => 'set_test123',
                    'status' => 'current',
                    'period_start' => 1640995200,
                    'period_end' => 1641081599,
                    'total_amount' => 150000,
                    'amount_paid' => 0,
                    'amount_payable' => 148500,
                    'total_number_of_transactions' => 50,
                    'number_of_transactions' => 45,
                    'number_of_refunds' => 3,
                    'number_of_mandates' => 2,
                    'total_volume' => 150000,
                    'transaction_volume' => 148000,
                    'refund_volume' => -2000,
                    'mandate_volume' => 4000,
                    'total_transaction_costs' => -1500,
                    'payout_type' => 'collective',
                    'livemode' => false,
                    'object' => 'settlement',
                ],
                [
                    'uid' => 'set_test456',
                    'status' => 'paid',
                    'period_start' => 1640908800,
                    'period_end' => 1640995199,
                    'total_amount' => 75000,
                    'amount_paid' => 74250,
                    'amount_payable' => 0,
                    'total_number_of_transactions' => 25,
                    'number_of_transactions' => 23,
                    'number_of_refunds' => 1,
                    'number_of_mandates' => 1,
                    'total_volume' => 75000,
                    'transaction_volume' => 74000,
                    'refund_volume' => -1000,
                    'mandate_volume' => 2000,
                    'total_transaction_costs' => -750,
                    'payout_type' => 'collective',
                    'livemode' => false,
                    'object' => 'settlement',
                ]
            ],
            'has_more' => false,
            'total_item_count' => 2,
            'items_per_page' => 10,
            'current_page' => 1,
            'last_page' => 1,
        ], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->settlements()->list();
    $settlements = $response->dto();

    expect($settlements->data)->toHaveCount(2);
    expect($settlements->data[0])->toBeInstanceOf(SettlementData::class);
    expect($settlements->data[0]->uid)->toBe('set_test123');
    expect($settlements->data[0]->status)->toBe('current');
    expect($settlements->data[0]->total_amount)->toBe(150000);
    expect($settlements->data[1]->uid)->toBe('set_test456');
    expect($settlements->data[1]->status)->toBe('paid');

    $mockClient->assertSent(GetSettlementsRequest::class);
});

it('can get settlement specification rows', function () {
    $mockClient = new MockClient([
        GetSettlementSpecificationRowsRequest::class => MockResponse::make([
            'object' => 'list',
            'data' => [
                [
                    'type' => 'transaction',
                    'reference' => 'txn_abc123',
                    'total_partner_fee' => 150,
                    'amount' => 5000,
                    'amount_payable' => 4850,
                    'metadata' => ['merchant_uid' => 'mer_test123'],
                ],
                [
                    'type' => 'refund',
                    'reference' => 'ref_def456',
                    'total_partner_fee' => 0,
                    'amount' => -1000,
                    'amount_payable' => -1000,
                    'metadata' => ['merchant_uid' => 'mer_test123'],
                ],
                [
                    'type' => 'mandate',
                    'reference' => 'man_ghi789',
                    'total_partner_fee' => 50,
                    'amount' => 2000,
                    'amount_payable' => 1950,
                    'metadata' => ['merchant_uid' => 'mer_test456'],
                ]
            ],
            'has_more' => false,
            'total_item_count' => 3,
            'items_per_page' => 10,
            'current_page' => 1,
            'last_page' => 1,
        ], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $settlementUid = 'set_test123';
    $specificationUid = 'spec_test456';
    
    $response = $this->connector->settlements()->specificationRows($settlementUid, $specificationUid);
    $rows = $response->dto();

    expect($rows->data)->toHaveCount(3);
    expect($rows->data[0])->toBeInstanceOf(SettlementRowData::class);
    expect($rows->data[0]->type)->toBe('transaction');
    expect($rows->data[0]->reference)->toBe('txn_abc123');
    expect($rows->data[0]->amount)->toBe(5000);
    expect($rows->data[0]->amount_payable)->toBe(4850);
    expect($rows->data[1]->type)->toBe('refund');
    expect($rows->data[1]->amount)->toBe(-1000);
    expect($rows->data[2]->type)->toBe('mandate');

    $mockClient->assertSent(GetSettlementSpecificationRowsRequest::class);
});

it('can filter settlements by status', function () {
    $mockClient = new MockClient([
        GetSettlementsRequest::class => MockResponse::make([
            'object' => 'list',
            'data' => [
                [
                    'uid' => 'set_current123',
                    'status' => 'current',
                    'period_start' => 1640995200,
                    'period_end' => 1641081599,
                    'total_amount' => 150000,
                    'amount_paid' => 0,
                    'amount_payable' => 148500,
                    'total_number_of_transactions' => 50,
                    'payout_type' => 'collective',
                    'livemode' => false,
                    'object' => 'settlement',
                ]
            ],
            'has_more' => false,
            'total_item_count' => 1,
            'items_per_page' => 10,
            'current_page' => 1,
            'last_page' => 1,
        ], 200),
    ]);

    $this->connector->withMockClient($mockClient);

    $response = $this->connector->settlements()->list([
        'filter' => ['status' => 'current'],
        'expand' => ['specifications'],
        'order' => ['-period']
    ]);

    $settlements = $response->dto();
    expect($settlements->data)->toHaveCount(1);
    expect($settlements->data[0]->status)->toBe('current');

    $mockClient->assertSent(function ($request) {
        return $request instanceof GetSettlementsRequest 
            && $request->query()->get('filter')['status'] === 'current'
            && in_array('specifications', $request->query()->get('expand', []))
            && in_array('-period', $request->query()->get('order', []));
    });
});