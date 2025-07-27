<?php

declare(strict_types=1);

/**
 * Response DTO scenarios with expected field mappings
 */
dataset('charge_response_scenarios', [
    'minimal charge' => [
        'data' => [
            'uid' => 'cha_123456789',
            'type' => 'balance',
            'status' => 'pending',
            'amount' => 1000,
            'currency' => 'EUR',
            'from_owner_uid' => 'mer_123',
            'to_owner_uid' => 'mer_456',
        ],
        'assertions' => [
            'uid' => 'cha_123456789',
            'type' => 'balance',
            'status' => 'pending',
            'amount' => 1000,
            'currency' => 'EUR',
            'from_owner_uid' => 'mer_123',
            'to_owner_uid' => 'mer_456',
        ],
    ],
    'complete charge' => [
        'data' => [
            'uid' => 'cha_987654321',
            'type' => 'balance',
            'status' => 'completed',
            'amount' => 2500,
            'currency' => 'EUR',
            'from_owner_uid' => 'mer_789',
            'to_owner_uid' => 'mer_012',
            'description' => 'Monthly fee',
            'metadata' => ['invoice' => 'INV-001'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:45:00Z',
        ],
        'assertions' => [
            'uid' => 'cha_987654321',
            'status' => 'completed',
            'amount' => 2500,
            'description' => 'Monthly fee',
            'metadata' => ['invoice' => 'INV-001'],
        ],
    ],
]);

dataset('transaction_response_scenarios', [
    'minimal transaction' => [
        'data' => [
            'uid' => 'tra_123456789',
            'status' => 'created',
            'merchant_uid' => 'mer_123456789',
            'amount' => 2500,
            'currency' => 'EUR',
        ],
        'assertions' => [
            'uid' => 'tra_123456789',
            'status' => 'created',
            'merchant_uid' => 'mer_123456789',
            'amount' => 2500,
            'currency' => 'EUR',
        ],
    ],
    'transaction with payment details' => [
        'data' => [
            'uid' => 'tra_987654321',
            'status' => 'completed',
            'merchant_uid' => 'mer_987654321',
            'amount' => 5000,
            'currency' => 'EUR',
            'payment_method' => 'ideal',
            'payment_flow' => 'hosted',
            'redirect_url' => 'https://payment.example.com/pay/tra_987654321',
            'has_checkout' => true,
            'livemode' => false,
            'created' => 1705315800,
            'completed' => 1705319500,
        ],
        'assertions' => [
            'uid' => 'tra_987654321',
            'status' => 'completed',
            'amount' => 5000,
            'payment_method' => 'ideal',
            'redirect_url' => 'https://payment.example.com/pay/tra_987654321',
            'has_checkout' => true,
            'livemode' => false,
        ],
    ],
]);

dataset('mandate_response_scenarios', [
    'pending mandate' => [
        'data' => [
            'uid' => 'man_123456789',
            'status' => 'pending',
            'merchant_uid' => 'mer_123456789',
            'mandate_url' => 'https://api.example.com/mandates/man_123456789',
        ],
        'assertions' => [
            'uid' => 'man_123456789',
            'status' => 'pending',
            'merchant_uid' => 'mer_123456789',
            'mandate_url' => 'https://api.example.com/mandates/man_123456789',
        ],
    ],
    'active mandate with details' => [
        'data' => [
            'uid' => 'man_987654321',
            'status' => 'active',
            'merchant_uid' => 'mer_987654321',
            'mandate_url' => 'https://api.example.com/mandates/man_987654321',
            'description' => 'Monthly subscription',
            'reference' => 'SUB-2024-001',
            'iban' => 'NL91ABNA0417164300',
            'bic' => 'ABNANL2A',
            'holder_name' => 'John Doe',
            'signed_at' => '2024-01-15T11:00:00Z',
        ],
        'assertions' => [
            'uid' => 'man_987654321',
            'status' => 'active',
            'iban' => 'NL91ABNA0417164300',
            'holder_name' => 'John Doe',
            'signed_at' => '2024-01-15T11:00:00Z',
        ],
    ],
]);

/**
 * Constructor parameter scenarios for Request DTOs
 */
dataset('create_transaction_params', [
    'minimal required' => [[
        'merchant_uid' => 'mer_123456789',
        'total_price' => 2500,
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
        'products' => [],
    ]],
    'with optional fields' => [[
        'merchant_uid' => 'mer_987654321',
        'total_price' => 5000,
        'return_url' => 'https://store.example.com/success',
        'notify_url' => 'https://store.example.com/webhook',
        'products' => [
            ['name' => 'Product 1', 'price' => 2500, 'quantity' => 1],
            ['name' => 'Product 2', 'price' => 2500, 'quantity' => 1],
        ],
        'payment_method' => 'ideal',
        'description' => 'Order #12345',
        'reference' => 'ORD-2024-12345',
        'metadata' => ['customer_id' => 'cust_123'],
    ]],
]);

dataset('create_charge_params', [
    'minimal charge' => [[
        'type' => 'balance',
        'amount' => 1000,
        'from_owner_uid' => 'mer_123',
        'to_owner_uid' => 'mer_456',
    ]],
    'charge with optional' => [[
        'type' => 'balance',
        'amount' => 2500,
        'from_owner_uid' => 'mer_789',
        'to_owner_uid' => 'mer_012',
        'description' => 'Platform fee',
        'metadata' => ['category' => 'commission', 'period' => '2024-01'],
    ]],
]);
