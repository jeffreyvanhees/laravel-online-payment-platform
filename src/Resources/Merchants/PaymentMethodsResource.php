<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\GetMerchantPaymentMethodsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing merchant payment methods
 *
 * Provides methods for retrieving payment methods available for a specific merchant.
 */
class PaymentMethodsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * List payment methods for the merchant
     *
     * @param  array  $params  Optional query parameters for filtering
     * @return Response API response containing a list of payment methods for this merchant
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMerchantPaymentMethodsRequest($this->merchantUid, $params));
    }
}
