<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements\GetSettlementSpecificationRowsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Settlements\GetSettlementsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing global settlements
 *
 * Provides methods for retrieving platform-wide settlement information and detailed
 * specification rows for financial reconciliation and reporting.
 */
class SettlementsResource extends BaseResource
{
    /**
     * List all settlements across the platform
     *
     * @param  array  $params  Optional query parameters for filtering settlements
     *                         - filter[status]: Filter by status (e.g., 'current', 'paid')
     *                         - expand[]: Expand related data (e.g., 'specifications')
     *                         - order[]: Ordering (e.g., '-period' for descending by period)
     * @return Response API response containing a list of settlements
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetSettlementsRequest($params));
    }

    /**
     * Get detailed specification rows for a specific settlement
     *
     * @param  string  $settlementUid  The unique identifier of the settlement
     * @param  string  $specificationUid  The unique identifier of the specification
     * @param  array  $params  Optional query parameters for filtering rows
     * @return Response API response containing detailed settlement row data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When settlement or specification is not found
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function specificationRows(string $settlementUid, string $specificationUid, array $params = []): Response
    {
        return $this->connector->send(new GetSettlementSpecificationRowsRequest($settlementUid, $specificationUid, $params));
    }
}
