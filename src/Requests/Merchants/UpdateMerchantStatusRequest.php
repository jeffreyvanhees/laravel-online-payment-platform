<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantStatusRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $status
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/update-status";
    }

    protected function defaultBody(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}
