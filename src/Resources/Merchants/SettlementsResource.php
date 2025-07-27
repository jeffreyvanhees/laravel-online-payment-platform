<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantSettlementsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing merchant settlements
 *
 * Provides methods for retrieving settlement information for merchants.
 */
class SettlementsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Get settlements for the merchant
     *
     * @param  array  $params  Optional query parameters for filtering settlements
     * @return Response API response containing the merchant's settlements
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMerchantSettlementsRequest($this->merchantUid, $params));
    }
}
