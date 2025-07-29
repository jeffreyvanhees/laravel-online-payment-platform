<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMerchantPaymentMethodsRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected array $params = []
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/payment_methods";
    }

    protected function defaultQuery(): array
    {
        return $this->params;
    }
}
