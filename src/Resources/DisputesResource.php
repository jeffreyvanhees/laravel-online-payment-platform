<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes\CreateDisputeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes\GetDisputeRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Disputes\GetDisputesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing disputes
 *
 * Provides methods for creating, retrieving, and listing disputes.
 * Handles dispute management including fraud, complaints, and other issues.
 */
class DisputesResource extends BaseResource
{
    /**
     * Create a new dispute
     *
     * @param  array  $data  Dispute data including reference, reason, message, contact details
     * @return Response API response containing the created dispute data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(array $data): Response
    {
        return $this->connector->send(new CreateDisputeRequest($data));
    }

    /**
     * Retrieve a specific dispute by UID
     *
     * @param  string  $disputeUid  The unique identifier of the dispute
     * @param  array  $params  Optional query parameters (e.g., expand messages)
     * @return Response API response containing the dispute data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When dispute is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $disputeUid, array $params = []): Response
    {
        return $this->connector->send(new GetDisputeRequest($disputeUid, $params));
    }

    /**
     * List disputes with optional filtering parameters
     *
     * @param  array  $params  Optional query parameters for filtering (e.g., status, reason, date_created)
     * @return Response API response containing a list of disputes
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetDisputesRequest($params));
    }
}
