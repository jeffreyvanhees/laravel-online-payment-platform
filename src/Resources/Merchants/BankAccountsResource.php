<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Resources\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants\AddMerchantBankAccountRequest;
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
     * @param  array  $data  Bank account data including iban, account_holder_name, etc.
     * @return Response API response containing the created bank account data
     *
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ValidationException When required bank account fields are missing
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\ApiException When merchant is not found or other API errors
     * @throws \JeffreyVanHees\OnlinePaymentPlatform\Exceptions\AuthenticationException When API key is invalid
     */
    public function add(array $data): Response
    {
        return $this->connector->send(new AddMerchantBankAccountRequest($this->merchantUid, $data));
    }
}
