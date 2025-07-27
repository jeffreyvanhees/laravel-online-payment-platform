<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class AddMerchantAddressRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/addresses";
    }

    protected function defaultBody(): array
    {
        return $this->data;
    }
}
