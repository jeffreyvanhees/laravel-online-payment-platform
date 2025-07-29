<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateMerchantContactData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantContactData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantContactStatusData;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\SandboxOnlyException;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\AddMerchantContactRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\UpdateMerchantContactRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\UpdateMerchantContactStatusRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing merchant contacts
 *
 * Provides methods for adding and managing contacts for merchants.
 */
class ContactsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Add a contact person to the merchant
     *
     * @param  CreateMerchantContactData|array  $data  Contact data including name, email, phone, etc.
     * @return Response API response containing the created contact data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required contact fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function add(CreateMerchantContactData|array $data): Response
    {
        return $this->connector->send(new AddMerchantContactRequest($this->merchantUid, $data));
    }

    /**
     * Update an existing contact
     *
     * @param  string  $contactUid  The unique identifier of the contact
     * @param  UpdateMerchantContactData|array  $data  Contact update data
     * @return Response API response containing the updated contact data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When update data is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When contact is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function update(string $contactUid, UpdateMerchantContactData|array $data): Response
    {
        return $this->connector->send(new UpdateMerchantContactRequest($this->merchantUid, $contactUid, $data));
    }

    /**
     * Update contact status (sandbox/testing only)
     *
     * This method allows forcing contact status changes in sandbox environment
     * for testing purposes. Supported status flows:
     * - pending -> unverified
     * - pending -> verified
     * - unverified -> verified
     *
     * @param  string  $contactUid  The unique identifier of the contact
     * @param  UpdateMerchantContactStatusData|array|string  $status  The new status to set (pending, verified, unverified)
     * @return Response API response containing the updated contact data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\SandboxOnlyException When not in sandbox environment
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When status is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When contact is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function updateStatus(string $contactUid, UpdateMerchantContactStatusData|array|string $status): Response
    {
        if (!$this->connector->isSandbox()) {
            throw new SandboxOnlyException('updateStatus method for contacts');
        }

        return $this->connector->send(new UpdateMerchantContactStatusRequest($this->merchantUid, $contactUid, $status));
    }
}
