<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateBusinessMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ProductData;
use Spatie\LaravelData\DataCollection;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantsResponse;

it('can create a consumer merchant DTO', function () {
    $dto = new CreateConsumerMerchantData(
        type: 'consumer',
        country: 'NL',
        emailaddress: 'test@example.com',
        first_name: 'John',
        last_name: 'Doe',
    );

    expect($dto->type)->toBe('consumer');
    expect($dto->country)->toBe('NL');
    expect($dto->emailaddress)->toBe('test@example.com');
    expect($dto->first_name)->toBe('John');
    expect($dto->last_name)->toBe('Doe');

    $array = $dto->toArray();
    expect($array)->toHaveKey('type');
    expect($array)->toHaveKey('country');
    expect($array)->toHaveKey('emailaddress');
});

it('can create a business merchant DTO', function () {
    $dto = new CreateBusinessMerchantData(
        type: 'business',
        country: 'NL',
        emailaddress: 'business@example.com',
        coc_nr: '12345678',
        legal_name: 'Test B.V.',
    );

    expect($dto->type)->toBe('business');
    expect($dto->coc_nr)->toBe('12345678');
    expect($dto->legal_name)->toBe('Test B.V.');
});

it('can create a transaction DTO with products', function () {
    $products = new DataCollection(ProductData::class, [
        new ProductData(
            name: 'Test Product',
            quantity: 1,
            price: 1000,
        ),
    ]);

    $dto = new CreateTransactionData(
        merchant_uid: 'mer_123',
        total_price: 1000,
        return_url: 'https://example.com/return',
        notify_url: 'https://example.com/notify',
        products: $products,
    );

    expect($dto->merchant_uid)->toBe('mer_123');
    expect($dto->total_price)->toBe(1000);
    expect($dto->products)->toHaveCount(1);
    expect($dto->products->first()->name)->toBe('Test Product');
});

it('can create merchant response DTO from array', function () {
    $data = [
        'uid' => 'mer_123',
        'type' => 'consumer',
        'status' => 'active',
        'country' => 'NL',
        'emailaddress' => 'test@example.com',
        'first_name' => 'John',
        'last_name' => 'Doe',
    ];

    $dto = MerchantData::from($data);

    expect($dto->uid)->toBe('mer_123');
    expect($dto->type)->toBe('consumer');
    expect($dto->status)->toBe('active');
    expect($dto->emailaddress)->toBe('test@example.com');
});

it('can create paginated merchants response DTO', function () {
    $data = [
        'object' => 'list',
        'url' => '/v1/merchants',
        'has_more' => false,
        'total_item_count' => 1,
        'items_per_page' => 10,
        'current_page' => 1,
        'last_page' => 1,
        'data' => [
            [
                'uid' => 'mer_123',
                'type' => 'consumer',
                'status' => 'active',
                'country' => 'NL',
                'emailaddress' => 'test@example.com',
            ],
        ],
    ];

    $dto = MerchantsResponse::from($data);

    expect($dto->object)->toBe('list');
    expect($dto->has_more)->toBeFalse();
    expect($dto->total_item_count)->toBe(1);
    expect($dto->data)->toHaveCount(1);
    expect($dto->data->first())->toBeInstanceOf(MerchantData::class);
    expect($dto->data->first()->uid)->toBe('mer_123');
});