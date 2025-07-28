<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Charges\CreateChargeData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\CreateMerchantChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantChargesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing merchant-specific charges
 *
 * Provides methods for creating and retrieving charges for a specific merchant.
 * Charges represent fees or costs associated with merchant transactions or services.
 */
class ChargesResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Create a new charge for the merchant
     *
     * @param  CreateChargeData|array  $data  Charge data including type, amount, from_owner_uid, to_owner_uid, etc.
     * @return Response API response containing the created charge data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(CreateChargeData|array $data): Response
    {
        return $this->connector->send(new CreateMerchantChargeRequest($this->merchantUid, $data));
    }

    /**
     * Retrieve a specific charge by UID for this merchant
     *
     * @param  string  $chargeUid  The unique identifier of the charge
     * @return Response API response containing the charge data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When charge is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $chargeUid): Response
    {
        return $this->connector->send(new GetMerchantChargeRequest($this->merchantUid, $chargeUid));
    }

    /**
     * List charges for this merchant with optional filtering parameters
     *
     * @param  array  $params  Optional query parameters for filtering (e.g., limit, offset, type)
     * @return Response API response containing a list of charges for this merchant
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMerchantChargesRequest($this->merchantUid, $params));
    }
}