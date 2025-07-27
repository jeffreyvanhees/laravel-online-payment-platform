<?php

declare(strict_types=1);

namespace JeffreyVanHees\OnlinePaymentPlatform\Requests\Merchants;

use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateBusinessMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Requests\Merchants\CreateConsumerMerchantData;
use JeffreyVanHees\OnlinePaymentPlatform\Data\Responses\Merchants\MerchantData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

class CreateMerchantRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    public function __construct(protected CreateConsumerMerchantData|CreateBusinessMerchantData|array $data) {}

    public function resolveEndpoint(): string
    {
        return '/merchants';
    }

    protected function defaultBody(): array
    {
        if ($this->data instanceof CreateConsumerMerchantData || $this->data instanceof CreateBusinessMerchantData) {
            return $this->data->toArray();
        }

        return $this->data;
    }

    public function createDtoFromResponse(Response $response): MerchantData
    {
        return MerchantData::from($response->json());
    }
}
