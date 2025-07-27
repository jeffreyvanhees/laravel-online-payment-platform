<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals\CreateWithdrawalRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals\DeleteWithdrawalRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals\GetWithdrawalRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Withdrawals\GetWithdrawalsRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Resource class for managing withdrawals
 *
 * Provides methods for creating, retrieving, listing, and deleting withdrawals.
 * Supports both merchant-specific and profile-specific withdrawals.
 */
class WithdrawalsResource extends BaseResource
{
    /**
     * Create a new withdrawal for a merchant
     *
     * @param  string  $merchantUid  The unique identifier of the merchant
     * @param  array  $data  Withdrawal data including amount, reference, etc.
     * @param  string|null  $profileUid  Optional profile UID for profile-specific withdrawals
     * @return Response API response containing the created withdrawal data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required fields are missing or invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function create(string $merchantUid, array $data, ?string $profileUid = null): Response
    {
        return $this->connector->send(new CreateWithdrawalRequest($merchantUid, $data, $profileUid));
    }

    /**
     * Retrieve a specific withdrawal by UID
     *
     * @param  string  $withdrawalUid  The unique identifier of the withdrawal
     * @return Response API response containing the withdrawal data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When withdrawal is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function get(string $withdrawalUid): Response
    {
        return $this->connector->send(new GetWithdrawalRequest($withdrawalUid));
    }

    /**
     * List withdrawals with optional filtering parameters
     *
     * @param  array  $params  Optional query parameters for filtering
     * @param  string|null  $merchantUid  Optional merchant UID to filter by merchant
     * @return Response API response containing a list of withdrawals
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException For other API errors
     */
    public function list(array $params = [], ?string $merchantUid = null): Response
    {
        return $this->connector->send(new GetWithdrawalsRequest($params, $merchantUid));
    }

    /**
     * Delete a withdrawal
     *
     * @param  string  $withdrawalUid  The unique identifier of the withdrawal
     * @return Response API response confirming the deletion
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When withdrawal is not found, cannot be deleted, or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function delete(string $withdrawalUid): Response
    {
        return $this->connector->send(new DeleteWithdrawalRequest($withdrawalUid));
    }
}
