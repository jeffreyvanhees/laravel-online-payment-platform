<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DeleteMerchantProfileRequest extends Request
{
    protected Method $method = Method::DELETE;

    public function __construct(
        protected string $merchantUid,
        protected string $profileUid
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/profiles/{$this->profileUid}";
    }
}
