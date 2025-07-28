<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Partners;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetPartnerMerchantBalanceRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected array $params = []
    ) {}

    public function resolveEndpoint(): string
    {
        return "/partners/merchants/{$this->merchantUid}/balance";
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}