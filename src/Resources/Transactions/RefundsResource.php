<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Transactions;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateRefundData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\CreateRefundRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\GetTransactionRefundsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing transaction refunds
 *
 * Provides methods for creating and retrieving refunds for a specific transaction.
 * Refunds can only be created for transactions with status 'planned', 'reserved', or 'completed'.
 */
class RefundsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $transactionUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Create a refund for the transaction
     *
     * @param  CreateRefundData|array  $data  Refund data including amount and optional description
     * @return Response API response containing the created refund data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When amount is invalid or exceeds transaction amount
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When transaction cannot be refunded or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function create(CreateRefundData|array $data): Response
    {
        return $this->connector->send(new CreateRefundRequest($this->transactionUid, $data));
    }

    /**
     * List all refunds for the transaction
     *
     * @param  array  $params  Optional query parameters for filtering refunds
     * @return Response API response containing a list of refunds for the transaction
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When transaction is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetTransactionRefundsRequest($this->transactionUid, $params));
    }
}
