<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\UpdateMerchantUBOData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\UBOData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class UpdateMerchantUBORequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(
        protected string $merchantUid,
        protected string $uboUid,
        protected UpdateMerchantUBOData|array $data
    ) {}

    public function resolveEndpoint(): string
    {
        return "/merchants/{$this->merchantUid}/ubos/{$this->uboUid}";
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof UpdateMerchantUBOData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): UBOData
    {
        return UBOData::from($response->json());
    }
}
