<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\AddMerchantContactRequest;
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
     * @param  array  $data  Contact data including name, email, phone, etc.
     * @return Response API response containing the created contact data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required contact fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function add(array $data): Response
    {
        return $this->connector->send(new AddMerchantContactRequest($this->merchantUid, $data));
    }
}
