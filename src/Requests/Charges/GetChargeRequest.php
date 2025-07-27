<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Charges;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class GetChargeRequest extends Request
{
    protected Method $method = Method::GET;

    public function __construct(protected string $chargeUid) {}

    public function resolveEndpoint(): string
    {
        return "/charges/{$this->chargeUid}";
    }
}
