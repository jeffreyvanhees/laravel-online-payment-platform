<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateBusinessMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\CreateMerchantRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\UpdateMerchantStatusRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\AddressesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\BankAccountsResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\ContactsResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\ProfilesResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\SettlementsResource;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants\UBOsResource;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing merchants
 *
 * Provides methods for creating, retrieving, and managing merchants,
 * including access to subresources for contacts, addresses, bank accounts, and settlements.
 */
class MerchantsResource extends BaseResource
{
    /**
     * Create a new merchant
     *
     * @param  CreateConsumerMerchantData|CreateBusinessMerchantData|array  $data  Merchant data including type, country, emailaddress, etc.
     * @return Response API response containing the created merchant data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(CreateConsumerMerchantData|CreateBusinessMerchantData|array $data): Response
    {
        return $this->connector->send(new CreateMerchantRequest($data));
    }

    /**
     * Retrieve a specific merchant by UID
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return Response API response containing the merchant data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $merchantUid): Response
    {
        return $this->connector->send(new GetMerchantRequest($merchantUid));
    }

    /**
     * List merchants with optional filtering parameters
     *
     * @param  array  $params  Optional query parameters for filtering (e.g., limit, offset, status)
     * @return Response API response containing a list of merchants
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMerchantsRequest($params));
    }

    /**
     * Access contacts subresource for a specific merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return ContactsResource Contacts subresource instance
     */
    public function contacts(string $merchantUid): ContactsResource
    {
        return new ContactsResource($this->connector, $merchantUid);
    }

    /**
     * Access addresses subresource for a specific merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return AddressesResource Addresses subresource instance
     */
    public function addresses(string $merchantUid): AddressesResource
    {
        return new AddressesResource($this->connector, $merchantUid);
    }

    /**
     * Access bank accounts subresource for a specific merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return BankAccountsResource Bank accounts subresource instance
     */
    public function bankAccounts(string $merchantUid): BankAccountsResource
    {
        return new BankAccountsResource($this->connector, $merchantUid);
    }

    /**
     * Access settlements subresource for a specific merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return SettlementsResource Settlements subresource instance
     */
    public function settlements(string $merchantUid): SettlementsResource
    {
        return new SettlementsResource($this->connector, $merchantUid);
    }

    /**
     * Access UBOs (Ultimate Beneficial Owners) subresource for a specific merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return UBOsResource UBOs subresource instance
     */
    public function ubos(string $merchantUid): UBOsResource
    {
        return new UBOsResource($this->connector, $merchantUid);
    }

    /**
     * Access profiles subresource for a specific merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @return ProfilesResource Profiles subresource instance
     */
    public function profiles(string $merchantUid): ProfilesResource
    {
        return new ProfilesResource($this->connector, $merchantUid);
    }

    /**
     * Update merchant status (sandbox/testing only)
     *
     * This method allows forcing merchant status changes in sandbox environment
     * for testing purposes. Supported statuses: pending, live, terminated, suspended, blocked
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @param  string  $status  The new status to set
     * @return Response API response confirming the status update
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When status is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function updateStatus(string $merchantUid, string $status): Response
    {
        return $this->connector->send(new UpdateMerchantStatusRequest($merchantUid, $status));
    }
}
