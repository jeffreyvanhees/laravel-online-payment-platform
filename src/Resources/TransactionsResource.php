<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Transactions\CreateTransactionData;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\CreateTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\DeleteTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\GetTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\GetTransactionsRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Transactions\UpdateTransactionRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Resources\Transactions\RefundsResource;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing transactions
 *
 * Provides methods for creating, retrieving, updating, and deleting transactions.
 * Supports various transaction types including one-time payments and recurring transactions.
 */
class TransactionsResource extends BaseResource
{
    /**
     * Create a new transaction
     *
     * @param  CreateTransactionData|array  $data  Transaction data including merchant_uid, total_price, products, etc.
     * @return Response API response containing the created transaction data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(CreateTransactionData|array $data): Response
    {
        return $this->connector->send(new CreateTransactionRequest($data));
    }

    /**
     * Retrieve a specific transaction by UID
     *
     * @param  string  $transactionUid  The unique identifier of the transaction
     * @return Response API response containing the transaction data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When transaction is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $transactionUid): Response
    {
        return $this->connector->send(new GetTransactionRequest($transactionUid));
    }

    /**
     * List transactions with optional filtering parameters
     *
     * @param  array  $params  Optional query parameters for filtering (e.g., limit, offset, status, merchant_uid)
     * @return Response API response containing a list of transactions
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetTransactionsRequest($params));
    }

    /**
     * Update an existing transaction (e.g., escrow date)
     *
     * @param  string  $transactionUid  The unique identifier of the transaction
     * @param  array  $data  Update data (e.g., escrow_date)
     * @return Response API response containing the updated transaction data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When update data is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When transaction is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function update(string $transactionUid, array $data): Response
    {
        return $this->connector->send(new UpdateTransactionRequest($transactionUid, $data));
    }

    /**
     * Delete a transaction (typically for SEPA transactions before processing)
     *
     * @param  string  $transactionUid  The unique identifier of the transaction
     * @return Response API response confirming the deletion
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When transaction is not found, cannot be deleted, or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function delete(string $transactionUid): Response
    {
        return $this->connector->send(new DeleteTransactionRequest($transactionUid));
    }

    /**
     * Access refunds subresource for a specific transaction
     *
     * @param  string  $transactionUid  The unique identifier of the transaction
     * @return RefundsResource Refunds subresource instance
     */
    public function refunds(string $transactionUid): RefundsResource
    {
        return new RefundsResource($this->connector, $transactionUid);
    }
}
