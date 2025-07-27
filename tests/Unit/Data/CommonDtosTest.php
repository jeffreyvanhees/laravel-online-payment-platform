<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\AddressData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\BankAccountData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\ContactData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\MetaData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\NameData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\PaginationData;

describe('Common DTOs', function () {
    test('it can create AddressData from array', function () {
        $data = AddressData::from([
            'type' => 'business',
            'address_line_1' => 'Main Street 123',
            'address_line_2' => 'Floor 2',
            'city' => 'Amsterdam',
            'zipcode' => '1000 AA',
            'country' => 'NLD',
            'state' => 'North Holland',
        ]);

        expect($data->type)->toBe('business');
        expect($data->address_line_1)->toBe('Main Street 123');
        expect($data->address_line_2)->toBe('Floor 2');
        expect($data->city)->toBe('Amsterdam');
        expect($data->zipcode)->toBe('1000 AA');
        expect($data->country)->toBe('NLD');
        expect($data->state)->toBe('North Holland');
    });

    test('it can create BankAccountData from array', function () {
        $data = BankAccountData::from([
            'return_url' => 'https://example.com/return',
            'type' => 'business',
            'iban' => 'NL91ABNA0417164300',
            'bic' => 'ABNANL2A',
            'holder_name' => 'John Doe B.V.',
        ]);

        expect($data->return_url)->toBe('https://example.com/return');
        expect($data->type)->toBe('business');
        expect($data->iban)->toBe('NL91ABNA0417164300');
        expect($data->bic)->toBe('ABNANL2A');
        expect($data->holder_name)->toBe('John Doe B.V.');
    });

    test('it can create ContactData from array', function () {
        $data = ContactData::from([
            'type' => 'representative',
            'gender' => 'm',
            'title' => 'mr',
            'name' => [
                'first' => 'John',
                'last' => 'Smith',
                'initials' => 'J.S.',
                'names_given' => 'John',
            ],
            'emailaddresses' => [
                ['emailaddress' => 'john@example.com'],
            ],
            'phonenumbers' => [
                ['phonenumber' => '+31612345678'],
            ],
        ]);

        expect($data->type)->toBe('representative');
        expect($data->gender)->toBe('m');
        expect($data->title)->toBe('mr');
        expect($data->name)->toBeInstanceOf(NameData::class);
        expect($data->name->first)->toBe('John');
        expect($data->name->last)->toBe('Smith');
        expect($data->emailaddresses)->toHaveCount(1);
        expect($data->phonenumbers)->toHaveCount(1);
    });

    test('it can create MetaData from array', function () {
        $paginationData = new PaginationData(
            object: 'list',
            url: '/api/merchants',
            has_more: true,
            total_item_count: 250,
            items_per_page: 25,
            current_page: 1,
            last_page: 10
        );

        $data = new MetaData(pagination: $paginationData);

        expect($data->pagination)->toBeInstanceOf(PaginationData::class);
        expect($data->pagination->current_page)->toBe(1);
        expect($data->pagination->last_page)->toBe(10);
        expect($data->pagination->items_per_page)->toBe(25);
        expect($data->pagination->total_item_count)->toBe(250);
    });

    test('it can create NameData from array', function () {
        $data = NameData::from([
            'first' => 'John',
            'last' => 'Doe',
            'initials' => 'J.D.',
            'prefix' => 'van',
        ]);

        expect($data->first)->toBe('John');
        expect($data->last)->toBe('Doe');
        expect($data->initials)->toBe('J.D.');
        expect($data->prefix)->toBe('van');
    });

    test('it can create PaginationData from array', function () {
        $data = PaginationData::from([
            'object' => 'list',
            'url' => '/api/merchants',
            'has_more' => true,
            'total_item_count' => 750,
            'items_per_page' => 50,
            'current_page' => 2,
            'last_page' => 15,
        ]);

        expect($data->object)->toBe('list');
        expect($data->url)->toBe('/api/merchants');
        expect($data->has_more)->toBe(true);
        expect($data->total_item_count)->toBe(750);
        expect($data->items_per_page)->toBe(50);
        expect($data->current_page)->toBe(2);
        expect($data->last_page)->toBe(15);
    });

    test('it can create nested ContactData with NameData', function () {
        $contactData = ContactData::from([
            'type' => 'representative',
            'gender' => 'f',
            'title' => 'mrs',
            'name' => [
                'first' => 'Jane',
                'last' => 'Smith',
                'initials' => 'J.S.',
                'prefix' => 'van',
            ],
            'emailaddresses' => [
                ['emailaddress' => 'jane@example.com'],
                ['emailaddress' => 'jane.smith@example.com'],
            ],
            'phonenumbers' => [
                ['phonenumber' => '+31612345678'],
                ['phonenumber' => '+31623456789'],
            ],
        ]);

        expect($contactData->name->first)->toBe('Jane');
        expect($contactData->name->last)->toBe('Smith');
        expect($contactData->name->initials)->toBe('J.S.');
        expect($contactData->name->prefix)->toBe('van');
        expect($contactData->emailaddresses)->toHaveCount(2);
        expect($contactData->phonenumbers)->toHaveCount(2);
    });

    test('it can handle optional fields in DTOs', function () {
        $addressData = AddressData::from([
            'type' => 'business',
            'address_line_1' => 'Main Street 123',
            'city' => 'Amsterdam',
            'zipcode' => '1000 AA',
            'country' => 'NLD',
            // address_line_2 and state are optional and not provided
        ]);

        expect($addressData->type)->toBe('business');
        expect($addressData->address_line_1)->toBe('Main Street 123');
        expect($addressData->city)->toBe('Amsterdam');
        expect($addressData->zipcode)->toBe('1000 AA');
        expect($addressData->country)->toBe('NLD');
        expect($addressData->address_line_2)->toBeNull();
        expect($addressData->state)->toBeNull();
    });
});
