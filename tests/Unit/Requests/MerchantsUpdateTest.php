<?php

declare(strict_types=1);

use JeffreyVanHees\OnlinePaymentPlatform\Data\Common;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants as RequestData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants as ResponseData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants as Requests;
use Saloon\Enums\Method;
use Saloon\Http\Response;

describe('Merchants Update Requests', function () {
    describe('Requests\UpdateMerchantAddressRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $addressUid = 'addr_123456789';
            $data = ['street' => 'Updated Street 123'];
            $request = new Requests\UpdateMerchantAddressRequest($merchantUid, $addressUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/addresses/{$addressUid}");
        });

        test('it accepts DTO and array data', function () {
            $dto = new RequestData\UpdateMerchantAddressData(
                line_1: 'New Street 456',
                city: 'Amsterdam',
                postal_code: '1000 AB'
            );
            $request = new Requests\UpdateMerchantAddressRequest('mer_123', 'addr_123', $dto);
            $body = $request->body()->all();

            expect($body['line_1'])->toBe('New Street 456');
            expect($body['city'])->toBe('Amsterdam');
            expect($body['postal_code'])->toBe('1000 AB');

            // Test with array
            $arrayData = ['line_1' => 'Array Street', 'city' => 'Utrecht'];
            $request2 = new Requests\UpdateMerchantAddressRequest('mer_123', 'addr_123', $arrayData);
            $body2 = $request2->body()->all();

            expect($body2['line_1'])->toBe('Array Street');
            expect($body2['city'])->toBe('Utrecht');
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'type' => 'business',
                'address_line_1' => 'Updated Street 123',
                'zipcode' => '1012AB',
                'city' => 'Amsterdam',
                'country' => 'NLD'
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new Requests\UpdateMerchantAddressRequest('mer_123', 'addr_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(Common\AddressData::class);
            expect($dto->type)->toBe('business');
            expect($dto->address_line_1)->toBe('Updated Street 123');
        });
    });

    describe('Requests\UpdateMerchantBankAccountRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $bankAccountUid = 'ba_123456789';
            $data = ['reference' => 'updated-ref'];
            $request = new Requests\UpdateMerchantBankAccountRequest($merchantUid, $bankAccountUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/bank_accounts/{$bankAccountUid}");
        });

        test('it accepts DTO and array data', function () {
            $dto = new RequestData\UpdateMerchantBankAccountData(
                reference: 'bank-ref-123',
                return_url: 'https://example.com/return',
                notify_url: 'https://example.com/notify'
            );
            $request = new Requests\UpdateMerchantBankAccountRequest('mer_123', 'ba_123', $dto);
            $body = $request->body()->all();

            expect($body['reference'])->toBe('bank-ref-123');
            expect($body['return_url'])->toBe('https://example.com/return');
            expect($body['notify_url'])->toBe('https://example.com/notify');
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'return_url' => 'https://example.com/return',
                'type' => 'iban',
                'iban' => 'NL91ABNA0417164300',
                'bic' => 'ABNANL2A',
                'holder_name' => 'John Doe'
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new Requests\UpdateMerchantBankAccountRequest('mer_123', 'ba_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(Common\BankAccountData::class);
            expect($dto->return_url)->toBe('https://example.com/return');
            expect($dto->iban)->toBe('NL91ABNA0417164300');
        });
    });

    describe('Requests\UpdateMerchantBankAccountStatusRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $bankAccountUid = 'ba_123456789';
            $status = 'approved';
            $request = new Requests\UpdateMerchantBankAccountStatusRequest($merchantUid, $bankAccountUid, $status);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/bank_accounts/{$bankAccountUid}/update-status");
        });

        test('it accepts DTO, array, and string data', function () {
            // Test with DTO
            $dto = new RequestData\UpdateMerchantBankAccountStatusData('approved');
            $request = new Requests\UpdateMerchantBankAccountStatusRequest('mer_123', 'ba_123', $dto);
            $body = $request->body()->all();
            expect($body['status'])->toBe('approved');

            // Test with string
            $request2 = new Requests\UpdateMerchantBankAccountStatusRequest('mer_123', 'ba_123', 'pending');
            $body2 = $request2->body()->all();
            expect($body2['status'])->toBe('pending');

            // Test with array
            $request3 = new Requests\UpdateMerchantBankAccountStatusRequest('mer_123', 'ba_123', ['status' => 'disapproved']);
            $body3 = $request3->body()->all();
            expect($body3['status'])->toBe('disapproved');
        });
    });

    describe('Requests\UpdateMerchantContactRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $contactUid = 'con_123456789';
            $data = ['name' => 'Updated Name'];
            $request = new Requests\UpdateMerchantContactRequest($merchantUid, $contactUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/contacts/{$contactUid}");
        });

        test('it accepts DTO and array data', function () {
            $dto = new RequestData\UpdateMerchantContactData(
                name_first: 'John',
                name_last: 'Updated',
                emailaddresses: [['emailaddress' => 'john.updated@example.com']],
                phonenumbers: [['phonenumber' => '+31612345678']]
            );
            $request = new Requests\UpdateMerchantContactRequest('mer_123', 'con_123', $dto);
            $body = $request->body()->all();

            expect($body['name_first'])->toBe('John');
            expect($body['name_last'])->toBe('Updated');
            expect($body['emailaddresses'])->toBe([['emailaddress' => 'john.updated@example.com']]);
            expect($body['phonenumbers'])->toBe([['phonenumber' => '+31612345678']]);
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'type' => 'primary',
                'gender' => 'male',
                'title' => 'Mr.',
                'name' => [
                    'first' => 'John',
                    'last' => 'Updated'
                ],
                'emailaddresses' => [['emailaddress' => 'updated@example.com']]
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new Requests\UpdateMerchantContactRequest('mer_123', 'con_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(Common\ContactData::class);
            expect($dto->type)->toBe('primary');
            expect($dto->name->first)->toBe('John');
            expect($dto->name->last)->toBe('Updated');
        });
    });

    describe('Requests\UpdateMerchantContactStatusRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $contactUid = 'con_123456789';
            $status = 'verified';
            $request = new Requests\UpdateMerchantContactStatusRequest($merchantUid, $contactUid, $status);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/contacts/{$contactUid}/update-status");
        });

        test('it accepts DTO, array, and string data', function () {
            // Test with DTO
            $dto = new RequestData\UpdateMerchantContactStatusData('verified');
            $request = new Requests\UpdateMerchantContactStatusRequest('mer_123', 'con_123', $dto);
            $body = $request->body()->all();
            expect($body['status'])->toBe('verified');

            // Test with string
            $request2 = new Requests\UpdateMerchantContactStatusRequest('mer_123', 'con_123', 'pending');
            $body2 = $request2->body()->all();
            expect($body2['status'])->toBe('pending');
        });
    });

    describe('Requests\UpdateMerchantProfileRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $profileUid = 'pro_123456789';
            $data = ['name' => 'Updated Profile'];
            $request = new Requests\UpdateMerchantProfileRequest($merchantUid, $profileUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/profiles/{$profileUid}");
        });

        test('it accepts DTO and array data', function () {
            $dto = new RequestData\UpdateMerchantProfileData(
                name: 'Updated Profile Name',
                description: 'Updated description',
                url: 'https://example.com',
                notify_url: 'https://example.com/notify'
            );
            $request = new Requests\UpdateMerchantProfileRequest('mer_123', 'pro_123', $dto);
            $body = $request->body()->all();

            expect($body['name'])->toBe('Updated Profile Name');
            expect($body['description'])->toBe('Updated description');
            expect($body['url'])->toBe('https://example.com');
            expect($body['notify_url'])->toBe('https://example.com/notify');
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'uid' => 'pro_123456789',
                'name' => 'Updated Profile',
                'description' => 'Updated description',
                'url' => 'https://example.com',
                'notify_url' => 'https://example.com/notify'
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new Requests\UpdateMerchantProfileRequest('mer_123', 'pro_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(ResponseData\ProfileData::class);
            expect($dto->name)->toBe('Updated Profile');
        });
    });

    describe('Requests\UpdateMerchantUBORequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $uboUid = 'ubo_123456789';
            $data = ['name' => 'Updated UBO'];
            $request = new Requests\UpdateMerchantUBORequest($merchantUid, $uboUid, $data);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/ubos/{$uboUid}");
        });

        test('it accepts DTO and array data', function () {
            $dto = new RequestData\UpdateMerchantUBOData(
                name_prefix: 'Mr.',
                name_first: 'John',
                name_last: 'Updated',
                birthdate: '1985-01-15',
                ownership_percentage: 35
            );
            $request = new Requests\UpdateMerchantUBORequest('mer_123', 'ubo_123', $dto);
            $body = $request->body()->all();

            expect($body['name_prefix'])->toBe('Mr.');
            expect($body['name_first'])->toBe('John');
            expect($body['name_last'])->toBe('Updated');
            expect($body['birthdate'])->toBe('1985-01-15');
            expect($body['ownership_percentage'])->toBe(35);
        });

        test('it can create DTO from response', function () {
            $responseData = [
                'uid' => 'ubo_123456789',
                'name_first' => 'John',
                'name_last' => 'Updated',
                'date_of_birth' => '1985-01-15',
                'country_of_residence' => 'NLD',
                'name_prefix' => 'Mr.',
                'percentage_of_shares' => 35.5
            ];

            $response = Mockery::mock(Response::class);
            $response->shouldReceive('json')->once()->andReturn($responseData);

            $request = new Requests\UpdateMerchantUBORequest('mer_123', 'ubo_123', []);
            $dto = $request->createDtoFromResponse($response);

            expect($dto)->toBeInstanceOf(ResponseData\UBOData::class);
            expect($dto->name_first)->toBe('John');
            expect($dto->name_last)->toBe('Updated');
        });
    });

    describe('Requests\UpdateMerchantUBOStatusRequest', function () {
        test('it has correct method and endpoint', function () {
            $merchantUid = 'mer_123456789';
            $uboUid = 'ubo_123456789';
            $status = 'verified';
            $request = new Requests\UpdateMerchantUBOStatusRequest($merchantUid, $uboUid, $status);

            expect($request->getMethod())->toBe(Method::POST);
            expect($request->resolveEndpoint())->toBe("/merchants/{$merchantUid}/ubos/{$uboUid}/update-status");
        });

        test('it accepts DTO, array, and string data', function () {
            // Test with DTO
            $dto = new RequestData\UpdateMerchantUBOStatusData('verified');
            $request = new Requests\UpdateMerchantUBOStatusRequest('mer_123', 'ubo_123', $dto);
            $body = $request->body()->all();
            expect($body['status'])->toBe('verified');

            // Test with string
            $request2 = new Requests\UpdateMerchantUBOStatusRequest('mer_123', 'ubo_123', 'pending');
            $body2 = $request2->body()->all();
            expect($body2['status'])->toBe('pending');
        });
    });
});