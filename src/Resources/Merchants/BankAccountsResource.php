<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateMerchantBankAccountData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantBankAccountData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantBankAccountStatusData;
use JeffreyVanHees\OnlinePaymentPlatform\Exceptions\SandboxOnlyException;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\AddMerchantBankAccountRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\UpdateMerchantBankAccountRequest;
use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\UpdateMerchantBankAccountStatusRequest;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

/**
 * Subresource for managing merchant bank accounts
 *
 * Provides methods for adding and managing bank accounts for merchants.
 */
class BankAccountsResource extends BaseResource
{
    public function __construct(
        \Saloon\Http\Connector $connector,
        protected string $merchantUid
    ) {
        parent::__construct($connector);
    }

    /**
     * Add a bank account to the merchant
     *
     * @param  CreateMerchantBankAccountData|array  $data  Bank account data including iban, account_holder_name, etc.
     * @return Response API response containing the created bank account data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required bank account fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function add(CreateMerchantBankAccountData|array $data): Response
    {
        return $this->connector->send(new AddMerchantBankAccountRequest($this->merchantUid, $data));
    }

    /**
     * Update an existing bank account
     *
     * @param  string  $bankAccountUid  The unique identifier of the bank account
     * @param  UpdateMerchantBankAccountData|array  $data  Bank account update data
     * @return Response API response containing the updated bank account data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When update data is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When bank account is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function update(string $bankAccountUid, UpdateMerchantBankAccountData|array $data): Response
    {
        return $this->connector->send(new UpdateMerchantBankAccountRequest($this->merchantUid, $bankAccountUid, $data));
    }

    /**
     * Update bank account status (sandbox/testing only)
     *
     * This method allows forcing bank account status changes in sandbox environment
     * for testing purposes. Supported status flows:
     * - new -> pending -> approved
     * - new -> pending -> disapproved
     * - approved -> disapproved -> approved
     *
     * @param  string  $bankAccountUid  The unique identifier of the bank account
     * @param  UpdateMerchantBankAccountStatusData|array|string  $status  The new status to set (pending, approved, disapproved)
     * @return Response API response containing the updated bank account data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\SandboxOnlyException When not in sandbox environment
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When status is invalid
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When bank account is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function updateStatus(string $bankAccountUid, UpdateMerchantBankAccountStatusData|array|string $status): Response
    {
        if (!$this->connector->isSandbox()) {
            throw new SandboxOnlyException('updateStatus method for bank accounts');
        }

        return $this->connector->send(new UpdateMerchantBankAccountStatusRequest($this->merchantUid, $bankAccountUid, $status));
    }
}
