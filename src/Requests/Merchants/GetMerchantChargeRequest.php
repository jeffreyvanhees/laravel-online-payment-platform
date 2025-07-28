<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetMerchantChargeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(
        protected string $merchantUid,
        protected string $chargeUid
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/charges/{$this->chargeUid}";
    }
}