<?php

declare(strict_types=1);

/**
 * Charge request scenarios
 */
dataset('charge_scenarios', [
    'minimal balance charge' => [[
        'type' => 'balance',
        'amount' => 1000,
        'from_owner_uid' => 'mer_123',
        'to_owner_uid' => 'mer_456',
    ]],
    'charge with description' => [[
        'type' => 'balance',
        'amount' => 2500,
        'from_owner_uid' => 'mer_789',
        'to_owner_uid' => 'mer_012',
        'description' => 'Monthly service fee',
    ]],
    'charge with metadata' => [[
        'type' => 'balance',
        'amount' => 5000,
        'from_owner_uid' => 'mer_345',
        'to_owner_uid' => 'mer_678',
        'description' => 'Platform commission',
        'metadata' => ['invoice_id' => 'INV-2024-001', 'category' => 'commission'],
    ]],
]);

/**
 * Dispute request scenarios
 */
dataset('dispute_scenarios', [
    'minimal dispute' => [[
        'transaction_uid' => 'tra_123456789',
        'reason' => 'duplicate',
    ]],
    'dispute with message' => [[
        'transaction_uid' => 'tra_987654321',
        'reason' => 'fraudulent',
        'message' => 'Unauthorized transaction detected',
    ]],
    'dispute with evidence' => [[
        'transaction_uid' => 'tra_555666777',
        'reason' => 'other',
        'message' => 'Customer complaint with proof',
        'evidence_file_uids' => ['fil_123', 'fil_456', 'fil_789'],
    ]],
]);

/**
 * Mandate request scenarios
 */
dataset('mandate_scenarios', [
    'minimal mandate' => [[
        'merchant_uid' => 'mer_123456789',
        'return_url' => 'https://example.com/return',
        'notify_url' => 'https://example.com/notify',
    ]],
    'mandate with description' => [[
        'merchant_uid' => 'mer_987654321',
        'return_url' => 'https://store.example.com/success',
        'notify_url' => 'https://store.example.com/webhook',
        'description' => 'Monthly subscription mandate',
        'reference' => 'SUB-2024-001',
    ]],
    'mandate with metadata' => [[
        'merchant_uid' => 'mer_111222333',
        'return_url' => 'https://app.example.com/mandates/return',
        'notify_url' => 'https://app.example.com/mandates/notify',
        'description' => 'Recurring payment authorization',
        'reference' => 'AUTH-MONTHLY-123',
        'metadata' => ['subscription_id' => 'sub_123', 'plan' => 'premium'],
    ]],
]);

/**
 * File upload scenarios
 */
dataset('file_upload_scenarios', [
    'minimal file' => [[
        'purpose' => 'identity_document',
        'filename' => 'passport.pdf',
    ]],
    'file with size' => [[
        'purpose' => 'bank_statement',
        'filename' => 'statement_jan_2024.pdf',
        'size' => 2048576,
    ]],
    'file with full details' => [[
        'purpose' => 'identity_document',
        'filename' => 'drivers_license.jpg',
        'size' => 1024000,
        'mime_type' => 'image/jpeg',
        'description' => 'Driver license front side for KYC verification',
    ]],
]);

/**
 * Query parameter scenarios for list endpoints
 */
dataset('pagination_params', [
    'default pagination' => [[]],
    'with limit' => [['limit' => 20]],
    'with offset' => [['offset' => 40]],
    'with limit and offset' => [['limit' => 50, 'offset' => 100]],
]);

dataset('date_filter_params', [
    'no date filters' => [[]],
    'created after' => [['created_after' => '2024-01-01']],
    'created before' => [['created_before' => '2024-12-31']],
    'date range' => [['created_after' => '2024-01-01', 'created_before' => '2024-12-31']],
    'with updated filters' => [[
        'created_after' => '2024-01-01',
        'created_before' => '2024-12-31',
        'updated_after' => '2024-06-01',
        'updated_before' => '2024-06-30',
    ]],
]);

dataset('status_filters', [
    'no status' => [[]],
    'active status' => [['status' => 'active']],
    'pending status' => [['status' => 'pending']],
    'completed status' => [['status' => 'completed']],
    'failed status' => [['status' => 'failed']],
]);

/**
 * Combined query scenarios
 */
dataset('complex_query_scenarios', function () {
    $scenarios = [];

    // Generate combinations of pagination, date filters, and status
    foreach (['default' => [], 'paginated' => ['limit' => 20, 'offset' => 40]] as $pagKey => $pagination) {
        foreach (['no_dates' => [], 'with_dates' => ['created_after' => '2024-01-01']] as $dateKey => $dates) {
            foreach (['all' => [], 'active' => ['status' => 'active']] as $statusKey => $status) {
                $key = "{$pagKey}_{$dateKey}_{$statusKey}";
                $scenarios[$key] = [array_merge($pagination, $dates, $status)];
            }
        }
    }

    return $scenarios;
});
