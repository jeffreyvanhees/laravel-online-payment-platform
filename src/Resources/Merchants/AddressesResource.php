<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\AddMerchantAddressRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing merchant addresses
 * 
 * Provides methods for adding and managing addresses for merchants.
 */
class AddressesResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Add an address to the merchant
     * 
     * @param array $data Address data including street, city, postal_code, country, etc.
     * @return Response API response containing the created address data
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required address fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function add(array $data): Response
    {
        return $this->connector->send(new AddMerchantAddressRequest($this->merchantUid, $data));
    }
}