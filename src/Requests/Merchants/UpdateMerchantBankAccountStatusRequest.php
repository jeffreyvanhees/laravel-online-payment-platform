<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantBankAccountStatusData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Common\BankAccountData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantBankAccountStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $bankAccountUid,
        protected UpdateMerchantBankAccountStatusData|array|string $statusData
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/bank_accounts/{$this->bankAccountUid}/update-status";
    }

    protected function defaultBody(): array
    {
        if ($this->statusData instanceof UpdateMerchantBankAccountStatusData) {
            return $this->statusData->toArray();
        }
        
        if (is_string($this->statusData)) {
            return ['status' => $this->statusData];
        }
        
        return $this->statusData;
    }

    public function createDtoFromResponse(Response $response): BankAccountData
    {
        return BankAccountData::from($response->json());
    }
}