<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected UpdateMerchantData|array $updateData
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->updateData instanceof UpdateMerchantData) {
            return $this->updateData->toArray();
        }

        return $this->updateData;
    }

    public function createDtoFromResponse(Response $response): MerchantData
    {
        return MerchantData::from($response->json());
    }
}
