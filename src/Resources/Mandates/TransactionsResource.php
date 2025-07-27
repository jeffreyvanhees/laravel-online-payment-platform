<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Mandates;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\CreateMandateTransactionRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing mandate transactions
 *
 * Provides methods for creating transactions using existing mandates.
 */
class TransactionsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $mandateUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Create a transaction using the mandate
     *
     * @param  array  $data  Transaction data including total_price, products, etc.
     * @return Response API response containing the created transaction data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When mandate is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function create(array $data): Response
    {
        return $this->connector->send(new CreateMandateTransactionRequest($this->mandateUid, $data));
    }
}
