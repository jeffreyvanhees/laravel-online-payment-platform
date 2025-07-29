<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantBankAccountData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\BankAccountData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantBankAccountRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $bankAccountUid,
        protected UpdateMerchantBankAccountData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/bank_accounts/{$this->bankAccountUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateMerchantBankAccountData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): BankAccountData
    {
        return BankAccountData::from($response->json());
    }
}