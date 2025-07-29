<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMerchantProfileBalanceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected string $profileUid,
        protected array $params = []
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/profiles/{$this->profileUid}/balance";
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}
