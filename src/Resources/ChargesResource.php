<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\CreateChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\GetChargeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges\GetChargesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing charges
 * 
 * Provides methods for creating, retrieving, and listing charges.
 * Charges represent fees or costs associated with transactions or services.
 */
class ChargesResource extends BaseResource
{
    /**
     * Create a new charge
     * 
     * @param array $data Charge data including type, amount, from_owner_uid, to_owner_uid, etc.
     * @return Response API response containing the created charge data
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(array $data): Response
    {
        return $this->connector->send(new CreateChargeRequest($data));
    }

    /**
     * Retrieve a specific charge by UID
     * 
     * @param string $chargeUid The unique identifier of the charge
     * @return Response API response containing the charge data
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When charge is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $chargeUid): Response
    {
        return $this->connector->send(new GetChargeRequest($chargeUid));
    }

    /**
     * List charges with optional filtering parameters
     * 
     * @param array $params Optional query parameters for filtering (e.g., limit, offset, type)
     * @return Response API response containing a list of charges
     * 
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetChargesRequest($params));
    }
}