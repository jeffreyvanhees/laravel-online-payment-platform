<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\CreateMerchantUBORequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantUBORequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantUBOsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing merchant Ultimate Beneficial Owners (UBOs)
 * 
 * UBOs are individuals who ultimately own or control a business merchant.
 * This resource handles creating, retrieving, and managing UBO information.
 */
class UBOsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Create a new UBO for the merchant
     * 
     * @param array $data UBO data including name, date of birth, residence, etc.
     * @return Response API response containing the created UBO data
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(array $data): Response
    {
        return $this->connector->send(new CreateMerchantUBORequest($this->merchantUid, $data));
    }

    /**
     * Retrieve a specific UBO by UID
     * 
     * @param string $uboUid The unique identifier of the UBO
     * @return Response API response containing the UBO data
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When UBO is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $uboUid): Response
    {
        return $this->connector->send(new GetMerchantUBORequest($this->merchantUid, $uboUid));
    }

    /**
     * List all UBOs for the merchant
     * 
     * @param array $params Optional query parameters for filtering
     * @return Response API response containing a list of UBOs
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMerchantUBOsRequest($this->merchantUid, $params));
    }
}