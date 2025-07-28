<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges\CreateChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Charges\ChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Mandates\MandateData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\ProfileData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\SettlementData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\RefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Transactions\TransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Withdrawals\WithdrawalData;

require_once __DIR__.'/../../Helpers/TestHelpers.php';
require_once __DIR__.'/../../Datasets/DtoDatasets.php';
require_once __DIR__.'/../../Datasets/ResponseDtoDatasets.php';

describe('DTOs', function () {

    describe('Request DTOs', function () {
        test('create charge data', function (array $params) {
            $dto = new CreateChargeData(...$params);

            expect($dto)->toBeInstanceOf(CreateChargeData::class);

            // Verify all constructor parameters are set correctly
            foreach ($params as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Test toArray conversion
            $array = $dto->toArray();
            foreach ($params as $key => $value) {
                expect($array)->toHaveKey($key);
                expect($array[$key])->toBe($value);
            }
        })->with('create_charge_params');

        test('create transaction data', function (array $params) {
            $dto = new CreateTransactionData(...$params);

            expect($dto)->toBeInstanceOf(CreateTransactionData::class);

            // Verify required fields
            expect($dto->merchant_uid)->toBe($params['merchant_uid']);
            expect($dto->total_price)->toBe($params['total_price']);
            expect($dto->return_url)->toBe($params['return_url']);
            expect($dto->notify_url)->toBe($params['notify_url']);

            // Verify optional fields if present
            if (isset($params['payment_method'])) {
                expect($dto->payment_method)->toBe($params['payment_method']);
            }
            if (isset($params['description'])) {
                expect($dto->description)->toBe($params['description']);
            }
            if (isset($params['metadata'])) {
                expect($dto->metadata)->toBe($params['metadata']);
            }
        })->with('create_transaction_params');
    });

    describe('Response DTOs - From DtoDatasets', function () {
        test('charge data from array', function (array $data, array $assertions) {
            $dto = ChargeData::from($data);

            expect($dto)->toBeInstanceOf(ChargeData::class);

            foreach ($assertions as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }
        })->with('charge_response_scenarios');

        test('transaction data from array', function (array $data, array $assertions) {
            $dto = TransactionData::from($data);

            expect($dto)->toBeInstanceOf(TransactionData::class);

            foreach ($assertions as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }
        })->with('transaction_response_scenarios');
    });

    describe('Response DTOs - From ResponseDtoDatasets', function () {
        test('mandate data', function (array $scenario) {
            $dto = MandateData::from($scenario['data']);

            expect($dto)->toBeInstanceOf(MandateData::class);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Verify toArray conversion
            $array = $dto->toArray();
            expect($array['uid'])->toBe($scenario['data']['uid']);
            expect($array['status'])->toBe($scenario['data']['status']);
        })->with('mandate_data_scenarios');

        test('profile data', function (array $scenario) {
            $dto = ProfileData::from($scenario['data']);

            expect($dto)->toBeInstanceOf(ProfileData::class);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Test JSON serialization
            $json = json_encode($dto);
            $decoded = json_decode($json, true);
            expect($decoded['uid'])->toBe($scenario['data']['uid']);
            expect($decoded['name'])->toBe($scenario['data']['name']);
        })->with('profile_data_scenarios');

        test('settlement data', function (array $scenario) {
            $dto = SettlementData::from($scenario['data']);

            expect($dto)->toBeInstanceOf(SettlementData::class);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Verify currency is uppercase
            expect($dto->currency)->toBe(strtoupper($scenario['data']['currency']));
        })->with('settlement_data_scenarios');

        test('refund data', function (array $scenario) {
            $dto = RefundData::from($scenario['data']);

            expect($dto)->toBeInstanceOf(RefundData::class);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Test optional fields handling
            $array = $dto->toArray();
            if (! isset($scenario['data']['processed_at'])) {
                expect($array)->not->toHaveKey('processed_at');
            }
        })->with('refund_data_scenarios');

        test('withdrawal data', function (array $scenario) {
            $dto = WithdrawalData::from($scenario['data']);

            expect($dto)->toBeInstanceOf(WithdrawalData::class);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Verify required fields are always present
            expect($dto->uid)->not->toBeNull();
            expect($dto->status)->not->toBeNull();
            expect($dto->amount)->not->toBeNull();
        })->with('withdrawal_data_scenarios');

        test('merchant data', function (array $scenario) {
            $dto = MerchantData::from($scenario['data']);

            expect($dto)->toBeInstanceOf(MerchantData::class);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Test type validation
            expect($dto->type)->toBeIn(['individual', 'business']);

            // Verify array conversion includes all fields
            $array = $dto->toArray();
            expect($array)->toHaveKey('uid');
            expect($array)->toHaveKey('type');
            expect($array)->toHaveKey('status');
        })->with('merchant_data_scenarios');
    });

    describe('Common DTOs', function () {
        test('common DTOs', function (array $scenario) {
            $dto = $scenario['class']::from($scenario['data']);

            expect($dto)->toBeInstanceOf($scenario['class']);

            foreach ($scenario['assertions'] as $property => $expectedValue) {
                expect($dto->$property)->toBe($expectedValue);
            }

            // Verify DTO can be recreated from its array representation
            $array = $dto->toArray();
            $recreated = $scenario['class']::from($array);
            expect($recreated->toArray())->toBe($array);
        })->with('common_dto_scenarios');
    });

    describe('Edge Cases and Validations', function () {
        // Test DTO collections
        test('DTO handles collections properly', function () {
            $data = [
                'uid' => 'set_123',
                'status' => 'completed',
                'amount' => 10000,
                'currency' => 'EUR',
                'date' => '2024-01-15',
                'transactions' => [
                    ['uid' => 'tra_1', 'total_price' => 5000],
                    ['uid' => 'tra_2', 'total_price' => 5000],
                ],
            ];

            $dto = SettlementData::from($data);

            expect($dto->transactions)->toBeArray();
            expect($dto->transactions)->toHaveCount(2);
            expect($dto->transactions[0]['uid'])->toBe('tra_1');
        });

        // Test nullable fields
        test('DTO handles nullable fields correctly', function () {
            $minimalData = [
                'uid' => 'man_123',
                'status' => 'pending',
                'merchant_uid' => 'mer_123',
                'mandate_url' => 'https://example.com/mandate',
            ];

            $dto = MandateData::from($minimalData);

            expect($dto->description)->toBeNull();
            expect($dto->reference)->toBeNull();
            expect($dto->metadata)->toBeNull();
            expect($dto->iban)->toBeNull();

            // Verify null fields are not included in array when null
            $array = $dto->toArray();
            expect($array)->not->toHaveKey('description');
            expect($array)->not->toHaveKey('reference');
        });

        // Test JSON serialization
        test('DTOs serialize to JSON correctly', function () {
            $data = [
                'uid' => 'mer_123',
                'type' => 'business',
                'status' => 'active',
                'country' => 'NLD',
                'legal_name' => 'Test Company B.V.',
            ];

            $dto = MerchantData::from($data);
            $json = json_encode($dto);
            $decoded = json_decode($json, true);

            expect($decoded['uid'])->toBe('mer_123');
            expect($decoded['type'])->toBe('business');
            expect($decoded['legal_name'])->toBe('Test Company B.V.');
        });

        // Test DTO validation with required fields
        test('dto validates required fields', function (string $dtoClass, array $requiredFields) {
            $reflection = new ReflectionClass($dtoClass);
            $constructor = $reflection->getConstructor();
            $parameters = $constructor->getParameters();

            $requiredParams = array_filter($parameters, fn ($param) => ! $param->isOptional());
            $requiredParamNames = array_map(fn ($param) => $param->getName(), $requiredParams);

            expect($requiredParamNames)->toBe($requiredFields);
        })->with([
            'CreateChargeData' => [
                CreateChargeData::class,
                ['type', 'amount', 'from_owner_uid', 'to_owner_uid'],
            ],
            'CreateTransactionData' => [
                CreateTransactionData::class,
                ['merchant_uid', 'total_price', 'return_url', 'notify_url', 'products'],
            ],
        ]);
    });
});
