<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantStatusData;
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
        protected UpdateMerchantStatusData|array|string $statusData
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/update-status";
    }

    protected function defaultBody(): array
    {
        if ($this->statusData instanceof UpdateMerchantStatusData) {
            return $this->statusData->toArray();
        }

        if (is_string($this->statusData)) {
            return ['status' => $this->statusData];
        }

        return $this->statusData;
    }
}
