<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\CreateMandateRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\DeleteMandateRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\GetMandateRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Mandates\GetMandatesRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing mandates
 *
 * Provides methods for creating, retrieving, deleting mandates and creating mandate transactions.
 * Mandates allow for recurring payments and direct debit authorization.
 */
class MandatesResource extends BaseResource
{
    /**
     * Create a new mandate
     *
     * @param  array  $data  Mandate data including merchant_uid, mandate_method, mandate_type, etc.
     * @return Response API response containing the created mandate data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(array $data): Response
    {
        return $this->connector->send(new CreateMandateRequest($data));
    }

    /**
     * Retrieve a specific mandate by UID
     *
     * @param  string  $mandateUid  The unique identifier of the mandate
     * @return Response API response containing the mandate data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When mandate is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $mandateUid): Response
    {
        return $this->connector->send(new GetMandateRequest($mandateUid));
    }

    /**
     * List mandates with optional filtering parameters
     *
     * @param  array  $params  Optional query parameters for filtering (e.g., limit, offset, status)
     * @return Response API response containing a list of mandates
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = []): Response
    {
        return $this->connector->send(new GetMandatesRequest($params));
    }

    /**
     * Delete a mandate
     *
     * @param  string  $mandateUid  The unique identifier of the mandate
     * @return Response API response confirming the deletion
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When mandate is not found, cannot be deleted, or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function delete(string $mandateUid): Response
    {
        return $this->connector->send(new DeleteMandateRequest($mandateUid));
    }

    /**
     * Access transactions subresource for a specific mandate
     *
     * @param  string  $mandateUid  The unique identifier of the mandate
     * @return \JeffreyVanHees\OnlinePaymentPlatform\Resources\Mandates\TransactionsResource Transactions subresource instance
     */
    public function transactions(string $mandateUid): \JeffreyVanHees\OnlinePaymentPlatform\Resources\Mandates\TransactionsResource
    {
        return new \JeffreyVanHees\OnlinePaymentPlatform\Resources\Mandates\TransactionsResource($this->connector, $mandateUid);
    }
}
