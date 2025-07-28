<?php

declare(strict_types=1);

/**
 * Comprehensive Response DTO test scenarios
 * Each scenario is wrapped in an array to pass as single parameter to test
 */
dataset('mandate_data_scenarios', [
    'minimal mandate' => [[
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
            'description' => null,
            'reference' => null,
            'metadata' => null,
            'iban' => null,
            'bic' => null,
            'holder_name' => null,
        ],
    ]],
    'active mandate with details' => [[
        'data' => [
            'uid' => 'man_987654321',
            'status' => 'active',
            'merchant_uid' => 'mer_987654321',
            'mandate_url' => 'https://api.example.com/mandates/man_987654321',
            'description' => 'Monthly subscription mandate',
            'reference' => 'REF-2024-001',
            'metadata' => ['subscription_id' => 'sub_123'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
            'signed_at' => '2024-01-15T11:00:00Z',
            'iban' => 'NL91ABNA0417164300',
            'bic' => 'ABNANL2A',
            'holder_name' => 'John Doe',
        ],
        'assertions' => [
            'uid' => 'man_987654321',
            'status' => 'active',
            'description' => 'Monthly subscription mandate',
            'reference' => 'REF-2024-001',
            'metadata' => ['subscription_id' => 'sub_123'],
            'signed_at' => '2024-01-15T11:00:00Z',
            'iban' => 'NL91ABNA0417164300',
            'holder_name' => 'John Doe',
        ],
    ]],
    'cancelled mandate' => [[
        'data' => [
            'uid' => 'man_cancelled_123',
            'status' => 'cancelled',
            'merchant_uid' => 'mer_123456789',
            'mandate_url' => 'https://api.example.com/mandates/man_cancelled_123',
            'created_at' => '2024-01-15T10:30:00Z',
            'cancelled_at' => '2024-01-20T15:45:00Z',
        ],
        'assertions' => [
            'uid' => 'man_cancelled_123',
            'status' => 'cancelled',
            'cancelled_at' => '2024-01-20T15:45:00Z',
        ],
    ]],
]);

dataset('profile_data_scenarios', [
    'minimal profile' => [[
        'data' => [
            'uid' => 'pro_123456789',
            'name' => 'Basic Profile',
        ],
        'assertions' => [
            'uid' => 'pro_123456789',
            'name' => 'Basic Profile',
            'description' => null,
            'settings' => null,
            'webhook_url' => null,
            'return_url' => null,
            'is_default' => null,
            'status' => null,
        ],
    ]],
    'complete profile' => [[
        'data' => [
            'uid' => 'pro_987654321',
            'name' => 'E-commerce Profile',
            'description' => 'Main profile for online store',
            'settings' => ['currency' => 'EUR', 'language' => 'en'],
            'webhook_url' => 'https://store.example.com/webhook',
            'return_url' => 'https://store.example.com/return',
            'is_default' => true,
            'status' => 'active',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T10:30:00Z',
        ],
        'assertions' => [
            'uid' => 'pro_987654321',
            'name' => 'E-commerce Profile',
            'description' => 'Main profile for online store',
            'settings' => ['currency' => 'EUR', 'language' => 'en'],
            'is_default' => true,
            'status' => 'active',
        ],
    ]],
    'inactive profile' => [[
        'data' => [
            'uid' => 'pro_inactive_123',
            'name' => 'Inactive Profile',
            'status' => 'inactive',
            'is_default' => false,
        ],
        'assertions' => [
            'status' => 'inactive',
            'is_default' => false,
        ],
    ]],
]);

dataset('settlement_data_scenarios', [
    'minimal settlement' => [[
        'data' => [
            'uid' => 'set_123456789',
            'status' => 'pending',
            'amount' => 25000,
            'currency' => 'EUR',
            'date' => '2024-01-20',
        ],
        'assertions' => [
            'uid' => 'set_123456789',
            'status' => 'pending',
            'amount' => 25000,
            'currency' => 'EUR',
            'date' => '2024-01-20',
            'bank_account_uid' => null,
            'transactions' => null,
        ],
    ]],
    'completed settlement' => [[
        'data' => [
            'uid' => 'set_987654321',
            'status' => 'completed',
            'amount' => 15000,
            'currency' => 'EUR',
            'date' => '2024-01-15',
            'bank_account_uid' => 'ban_123456789',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T14:30:00Z',
            'transactions' => [
                ['uid' => 'tra_123', 'amount' => 10000],
                ['uid' => 'tra_456', 'amount' => 5000],
            ],
        ],
        'assertions' => [
            'status' => 'completed',
            'amount' => 15000,
            'bank_account_uid' => 'ban_123456789',
            'transactions' => [
                ['uid' => 'tra_123', 'amount' => 10000],
                ['uid' => 'tra_456', 'amount' => 5000],
            ],
        ],
    ]],
    'failed settlement' => [[
        'data' => [
            'uid' => 'set_failed_123',
            'status' => 'failed',
            'amount' => 5000,
            'currency' => 'EUR',
            'date' => '2024-01-15',
        ],
        'assertions' => [
            'status' => 'failed',
            'amount' => 5000,
        ],
    ]],
]);

dataset('refund_data_scenarios', [
    'minimal refund' => [[
        'data' => [
            'uid' => 'ref_123456789',
            'status' => 'pending',
            'amount' => 1000,
            'currency' => 'EUR',
        ],
        'assertions' => [
            'uid' => 'ref_123456789',
            'status' => 'pending',
            'amount' => 1000,
            'currency' => 'EUR',
            'payout_description' => null,
            'message' => null,
            'processed_at' => null,
        ],
    ]],
    'completed refund' => [[
        'data' => [
            'uid' => 'ref_987654321',
            'status' => 'completed',
            'amount' => 2500,
            'currency' => 'EUR',
            'payout_description' => 'Refund for order #12345',
            'message' => 'Customer requested refund due to defective item',
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T14:30:00Z',
            'processed_at' => '2024-01-15T14:45:00Z',
        ],
        'assertions' => [
            'status' => 'completed',
            'amount' => 2500,
            'payout_description' => 'Refund for order #12345',
            'message' => 'Customer requested refund due to defective item',
            'processed_at' => '2024-01-15T14:45:00Z',
        ],
    ]],
    'failed refund' => [[
        'data' => [
            'uid' => 'ref_failed_123',
            'status' => 'failed',
            'amount' => 1500,
            'currency' => 'EUR',
            'message' => 'Insufficient funds for refund',
        ],
        'assertions' => [
            'status' => 'failed',
            'message' => 'Insufficient funds for refund',
            'processed_at' => null,
        ],
    ]],
]);

dataset('withdrawal_data_scenarios', [
    'minimal withdrawal' => [[
        'data' => [
            'uid' => 'wit_123456789',
            'status' => 'pending',
            'merchant_uid' => 'mer_123456789',
            'amount' => 25000,
            'currency' => 'EUR',
            'bank_account_uid' => 'ban_123456789',
        ],
        'assertions' => [
            'uid' => 'wit_123456789',
            'status' => 'pending',
            'merchant_uid' => 'mer_123456789',
            'amount' => 25000,
            'currency' => 'EUR',
            'bank_account_uid' => 'ban_123456789',
            'description' => null,
            'reference' => null,
            'metadata' => null,
        ],
    ]],
    'completed withdrawal' => [[
        'data' => [
            'uid' => 'wit_987654321',
            'status' => 'completed',
            'merchant_uid' => 'mer_987654321',
            'amount' => 50000,
            'currency' => 'EUR',
            'bank_account_uid' => 'ban_987654321',
            'description' => 'Monthly withdrawal',
            'reference' => 'WIT-2024-001',
            'metadata' => ['batch_id' => 'batch_123', 'priority' => 'high'],
            'created_at' => '2024-01-15T10:30:00Z',
            'updated_at' => '2024-01-15T14:30:00Z',
            'processed_at' => '2024-01-15T14:45:00Z',
        ],
        'assertions' => [
            'status' => 'completed',
            'amount' => 50000,
            'description' => 'Monthly withdrawal',
            'reference' => 'WIT-2024-001',
            'metadata' => ['batch_id' => 'batch_123', 'priority' => 'high'],
            'processed_at' => '2024-01-15T14:45:00Z',
        ],
    ]],
    'failed withdrawal' => [[
        'data' => [
            'uid' => 'wit_failed_123',
            'status' => 'failed',
            'merchant_uid' => 'mer_123456789',
            'amount' => 10000,
            'currency' => 'EUR',
            'bank_account_uid' => 'ban_123456789',
            'description' => 'Failed withdrawal attempt',
        ],
        'assertions' => [
            'status' => 'failed',
            'description' => 'Failed withdrawal attempt',
            'processed_at' => null,
        ],
    ]],
]);

dataset('merchant_data_scenarios', [
    'minimal merchant' => [[
        'data' => [
            'uid' => 'mer_123456789',
            'type' => 'individual',
            'status' => 'pending',
            'country' => 'NLD',
            'name' => 'John Tester',
        ],
        'assertions' => [
            'uid' => 'mer_123456789',
            'type' => 'individual',
            'status' => 'pending',
            'country' => 'NLD',
            'name' => 'John Tester',
            'emailaddress' => null,
            'coc_nr' => null,
            'vat_nr' => null,
        ],
    ]],
    'business merchant' => [[
        'data' => [
            'uid' => 'mer_987654321',
            'type' => 'business',
            'status' => 'active',
            'country' => 'NLD',
            'emailaddress' => 'merchant@example.com',
            'name' => 'Example Company B.V.',
            'name_first' => 'John',
            'name_last' => 'Doe',
            'coc_nr' => '12345678',
            'vat_nr' => 'NL123456789B01',
            'legal_name' => 'Example Company B.V.',
            'trading_names' => ['Example Store', 'Example Shop'],
            'phone' => '+31612345678',
            'language' => 'en',
            'reference' => 'REF-123',
            'notify_url' => 'https://example.com/notify',
            'return_url' => 'https://example.com/return',
            'metadata' => ['segment' => 'retail', 'tier' => 'premium'],
        ],
        'assertions' => [
            'type' => 'business',
            'status' => 'active',
            'legal_name' => 'Example Company B.V.',
            'coc_nr' => '12345678',
            'vat_nr' => 'NL123456789B01',
            'trading_names' => ['Example Store', 'Example Shop'],
        ],
    ]],
    'suspended merchant' => [[
        'data' => [
            'uid' => 'mer_suspended_123',
            'type' => 'business',
            'status' => 'suspended',
            'country' => 'NLD',
            'name' => 'Suspended Company B.V.',
            'legal_name' => 'Suspended Company B.V.',
        ],
        'assertions' => [
            'status' => 'suspended',
            'legal_name' => 'Suspended Company B.V.',
        ],
    ]],
]);

dataset('common_dto_scenarios', [
    'address' => [[
        'class' => 'JeffreyVanHees\OnlinePaymentPlatform\Data\Common\AddressData',
        'data' => [
            'type' => 'billing',
            'address_line_1' => 'Main Street 123',
            'zipcode' => '1234 AB',
            'city' => 'Amsterdam',
            'country' => 'NLD',
            'address_line_2' => 'Suite 100',
            'state' => 'Noord-Holland',
        ],
        'assertions' => [
            'type' => 'billing',
            'address_line_1' => 'Main Street 123',
            'city' => 'Amsterdam',
            'zipcode' => '1234 AB',
            'country' => 'NLD',
        ],
    ]],
    'contact' => [[
        'class' => 'JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ContactData',
        'data' => [
            'type' => 'primary',
            'gender' => 'F',
            'title' => 'Ms',
            'name' => [
                'first' => 'Jane',
                'last' => 'Smith',
            ],
            'birthdate' => '1990-01-15',
            'emailaddresses' => ['jane@example.com'],
            'phonenumbers' => ['+31612345678'],
        ],
        'assertions' => [
            'type' => 'primary',
            'gender' => 'F',
            'title' => 'Ms',
        ],
    ]],
    'bank_account' => [[
        'class' => 'JeffreyVanHees\OnlinePaymentPlatform\Data\Common\BankAccountData',
        'data' => [
            'return_url' => 'https://example.com/bank-return',
            'type' => 'sepa',
            'iban' => 'NL91ABNA0417164300',
            'bic' => 'ABNANL2A',
            'holder_name' => 'John Doe',
        ],
        'assertions' => [
            'return_url' => 'https://example.com/bank-return',
            'iban' => 'NL91ABNA0417164300',
            'holder_name' => 'John Doe',
        ],
    ]],
]);
